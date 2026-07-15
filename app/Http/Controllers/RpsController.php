<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\RpsDocument;
use App\Models\RpsWeek;
use App\Models\StudyProgram;
use App\Agents\RpsGeneratorAgent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Gate;

class RpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): InertiaResponse
    {
        $user = $request->user();
        $query = RpsDocument::with(['course.studyProgram', 'user']);

        if ($user && !$user->hasRole('admin') && !$user->hasPermissionTo('view all rps')) {
            $query->where('user_id', $user->id);
        }

        $documents = $query->orderBy('created_at', 'desc')->get();
        $courses = Course::with('studyProgram')->get();

        return Inertia::render('Rps/Index', [
            'documents' => $documents,
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): InertiaResponse
    {
        Gate::authorize('create', RpsDocument::class);

        $studyPrograms = StudyProgram::with('cpls')->get();
        
        $selectedCourseId = $request->query('course_id');
        $selectedCourse = $selectedCourseId 
            ? Course::with('cpls')->find($selectedCourseId) 
            : null;

        return Inertia::render('Rps/SelectCourse', [
            'studyPrograms' => $studyPrograms,
            'selectedCourse' => $selectedCourse,
            'courses' => Course::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', RpsDocument::class);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'cpls' => 'required|array|min:1',
            'cpls.*.code' => 'required|string',
            'cpls.*.description' => 'required|string',
            'cpls.*.type' => 'required|string',
        ]);

        $course = Course::where('id', $request->course_id)->firstOrFail();
        $user = $request->user();

        // 1. Save or sync manual CPLs for this course
        $cplIds = [];
        foreach ($request->cpls as $cplData) {
            $cpl = Cpl::firstOrCreate(
                [
                    'study_program_id' => $course->study_program_id,
                    'code' => $cplData['code'],
                ],
                [
                    'description' => $cplData['description'],
                    'type' => $cplData['type'],
                ]
            );
            $cplIds[] = $cpl->id;
        }
        $course->cpls()->sync($cplIds);

        // 2. Prepare prompt for AI Agent
        $cplStrings = array_map(function (array $c): string {
            return "[{$c['code']}] ({$c['type']}): {$c['description']}";
        }, $request->cpls);
        $cplsString = implode("\n", $cplStrings);

        $prompt = <<<PROMPT
Generate CPMK and a 14-week learning plan for the following course:
Course Code: {$course->code}
Course Name: {$course->name}
SKS: {$course->sks}
Semester: {$course->semester}
Description: {$course->description}

Graduate Learning Outcomes (CPL) targeted by this course:
{$cplsString}
PROMPT;

        // 3. Call AI Agent
        $agent = new RpsGeneratorAgent();
        $response = $agent->prompt($prompt);

        $cpmksData = [];
        $weeksData = [];

        if ($response instanceof \Laravel\Ai\Responses\StructuredAgentResponse) {
            $cpmksData = $response['cpmks'] ?? [];
            $weeksData = $response['weeks'] ?? [];
        }

        // 4. Save to Database
        DB::transaction(function () use ($course, $user, $cpmksData, $weeksData, $cplIds) {
            // Delete old documents of the same course
            RpsDocument::where('course_id', $course->id)->delete();

            $document = RpsDocument::create([
                'course_id' => $course->id,
                'user_id' => $user?->id,
                'status' => 'draft',
                'generated_at' => now(),
            ]);

            // Save CPMKs
            // Delete existing course cpmks first
            Cpmk::where('course_id', $course->id)->delete();

            foreach ($cpmksData as $cpmk) {
                // Find matching cpl_id by code
                $cpl = Cpl::where('study_program_id', $course->study_program_id)
                    ->where('code', $cpmk['cpl_code'])
                    ->first();

                Cpmk::create([
                    'course_id' => $course->id,
                    'cpl_id' => $cpl ? $cpl->id : ($cplIds[0] ?? null),
                    'statement' => $cpmk['statement'],
                    'indicators' => $cpmk['indicators'],
                ]);
            }

            // Save Weeks
            foreach ($weeksData as $week) {
                RpsWeek::create([
                    'rps_document_id' => $document->id,
                    'week_number' => $week['week_number'],
                    'sub_cpmk' => $week['sub_cpmk'],
                    'learning_methods' => $week['learning_methods'],
                    'assessment_criteria' => $week['assessment_criteria'],
                    'weight' => $week['weight'],
                    'resources' => $week['resources'],
                ]);
            }
        });

        $document = RpsDocument::where('course_id', $course->id)->firstOrFail();

        return redirect()->route('rps.edit', $document->id)
            ->with('success', 'RPS successfully generated by Gemini AI!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): InertiaResponse
    {
        $document = RpsDocument::with([
            'course.studyProgram',
            'weeks' => fn($q) => $q->orderBy('week_number', 'asc'),
        ])->findOrFail($id);

        Gate::authorize('update', $document);

        $cpmks = Cpmk::with('cpl')
            ->where('course_id', $document->course_id)
            ->get();

        return Inertia::render('Rps/Edit', [
            'document' => $document,
            'cpmks' => $cpmks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:draft,published',
            'cpmks' => 'required|array',
            'cpmks.*.id' => 'required|exists:cpmks,id',
            'cpmks.*.statement' => 'required|string',
            'cpmks.*.indicators' => 'required|string',
            'weeks' => 'required|array',
            'weeks.*.id' => 'required|exists:rps_weeks,id',
            'weeks.*.sub_cpmk' => 'required|string',
            'weeks.*.learning_methods' => 'nullable|string',
            'weeks.*.assessment_criteria' => 'nullable|string',
            'weeks.*.weight' => 'required|integer|min:0|max:100',
            'weeks.*.resources' => 'nullable|string',
        ]);

        $document = RpsDocument::findOrFail($id);

        Gate::authorize('update', $document);

        DB::transaction(function () use ($request, $document) {
            $document->update([
                'status' => $request->status,
            ]);

            foreach ($request->cpmks as $cpmkData) {
                Cpmk::where('id', $cpmkData['id'])->update([
                    'statement' => $cpmkData['statement'],
                    'indicators' => $cpmkData['indicators'],
                ]);
            }

            foreach ($request->weeks as $weekData) {
                RpsWeek::where('id', $weekData['id'])->update([
                    'sub_cpmk' => $weekData['sub_cpmk'],
                    'learning_methods' => $weekData['learning_methods'],
                    'assessment_criteria' => $weekData['assessment_criteria'],
                    'weight' => $weekData['weight'],
                    'resources' => $weekData['resources'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'RPS successfully updated!');
    }

    /**
     * Export the specified resource to PDF or DOCX.
     */
    public function export(int $id, string $format): \Symfony\Component\HttpFoundation\Response
    {
        $document = RpsDocument::with([
            'course.studyProgram.cpls',
            'weeks' => fn($q) => $q->orderBy('week_number', 'asc'),
        ])->findOrFail($id);

        Gate::authorize('view', $document);

        $cpmks = Cpmk::with('cpl')
            ->where('course_id', $document->course_id)
            ->get();

        if ($format === 'pdf') {
            return $this->exportToPdf($document, $cpmks);
        }

        if ($format === 'docx') {
            return $this->exportToDocx($document, $cpmks);
        }

        return abort(400, 'Unsupported export format.');
    }

    /**
     * Export to PDF using Dompdf.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cpmk>  $cpmks
     */
    protected function exportToPdf(RpsDocument $document, iterable $cpmks): \Illuminate\Http\Response
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);

        $html = view('exports.rps-pdf', [
            'document' => $document,
            'cpmks' => $cpmks,
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'RPS_' . str_replace(' ', '_', $document->course->name) . '.pdf';
        
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export to DOCX using PhpWord.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cpmk>  $cpmks
     */
    protected function exportToDocx(RpsDocument $document, iterable $cpmks): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $templatePath = public_path('FORMAT RPS OBE 2026.docx');
        
        if (!file_exists($templatePath)) {
            return abort(404, 'RPS DOCX Template file not found.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Fill basic placeholder values
        $templateProcessor->setValue('course_name', $document->course->name);
        $templateProcessor->setValue('course_code', $document->course->code);
        $templateProcessor->setValue('sks', $document->course->sks);
        $templateProcessor->setValue('semester', $document->course->semester);
        $templateProcessor->setValue('description', $document->course->description);
        $templateProcessor->setValue('program_name', $document->course->studyProgram->name);

        // Map CPLs
        $cplTexts = [];
        foreach ($document->course->cpls as $cpl) {
            $cplTexts[] = "{$cpl->code}: {$cpl->description}";
        }
        $templateProcessor->setValue('cpl_list', implode("\n", $cplTexts));

        // Map CPMKs
        $cpmkTexts = [];
        foreach ($cpmks as $index => $cpmk) {
            $num = $index + 1;
            $cpmkTexts[] = "CPMK{$num}: {$cpmk->statement} (Mendukung CPL: {$cpmk->cpl?->code})";
        }
        $templateProcessor->setValue('cpmk_list', implode("\n", $cpmkTexts));

        // Since PhpWord cloning can be complex, we can also put a placeholder or clone block
        // In the weekly table, we will replace weekly values or if the table is structured, we clone rows
        // Let's check if the template has `week_number` table row. Usually we can clone rows using cloneRow:
        // $templateProcessor->cloneRow('w_num', count($document->weeks));
        // For simplicity and compatibility, we will serialize the weekly plan to simple table representation
        // or support row cloning if 'w_num' is in the template. Let's do row cloning if possible:
        try {
            $weeksCount = count($document->weeks);
            if ($weeksCount > 0) {
                // We will try to clone row based on the placeholder 'w_num' (week number)
                $templateProcessor->cloneRow('w_num', $weeksCount);
                foreach ($document->weeks as $index => $week) {
                    $rowNum = $index + 1;
                    $templateProcessor->setValue('w_num#' . $rowNum, $week->week_number);
                    $templateProcessor->setValue('w_sub#' . $rowNum, $week->sub_cpmk);
                    $templateProcessor->setValue('w_method#' . $rowNum, $week->learning_methods);
                    $templateProcessor->setValue('w_criteria#' . $rowNum, $week->assessment_criteria);
                    $templateProcessor->setValue('w_weight#' . $rowNum, $week->weight . '%');
                    $templateProcessor->setValue('w_resources#' . $rowNum, $week->resources);
                }
            }
        } catch (\Exception $e) {
            // Fallback if the template doesn't support row cloning for w_num
            $fallbackText = "";
            foreach ($document->weeks as $week) {
                $fallbackText .= "Minggu {$week->week_number}:\nSub-CPMK: {$week->sub_cpmk}\nMetode: {$week->learning_methods}\nPenilaian: {$week->assessment_criteria} (Bobot: {$week->weight}%)\nBahan Kajian: {$week->resources}\n\n";
            }
            $templateProcessor->setValue('weekly_plans', $fallbackText);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'RPS_') . '.docx';
        $templateProcessor->saveAs($tempFile);

        $filename = 'RPS_' . str_replace(' ', '_', $document->course->name) . '.docx';

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
