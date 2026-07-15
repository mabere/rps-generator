<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Cpl;
use App\Models\RpsDocument;
use App\Models\StudyProgram;
use App\Models\User;
use App\Agents\RpsGeneratorAgent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RpsGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    }

    public function test_lecturer_can_generate_rps_with_ai_faking()
    {
        $user = User::factory()->create();
        $user->assignRole('lecturer');

        $program = StudyProgram::create([
            'name' => 'Pendidikan Bahasa Inggris',
            'code' => 'PBI',
        ]);

        $cpl = Cpl::create([
            'study_program_id' => $program->id,
            'code' => 'P1',
            'type' => 'Pengetahuan',
            'description' => 'Menguasai konsep kebahasaan.',
        ]);

        $course = Course::create([
            'study_program_id' => $program->id,
            'code' => 'ENG201',
            'name' => 'English Phonology',
            'sks' => 2,
            'semester' => 3,
            'description' => 'Course description.',
        ]);

        // Fake AI Agent response matching structured output schema
        RpsGeneratorAgent::fake([
            [
                'cpmks' => [
                    [
                        'cpl_code' => 'P1',
                        'statement' => 'Mahasiswa mampu mengidentifikasi fonem bahasa Inggris.',
                        'indicators' => 'Ketepatan identifikasi fonem.',
                    ]
                ],
                'weeks' => [
                    [
                        'week_number' => 1,
                        'sub_cpmk' => 'Mahasiswa mampu membedakan fonetik dan fonologi.',
                        'learning_methods' => 'Lecture & Discussion',
                        'assessment_criteria' => 'Rubrik keaktifan',
                        'weight' => 5,
                        'resources' => 'Roach (2009)',
                    ],
                    [
                        'week_number' => 2,
                        'sub_cpmk' => 'Mahasiswa mampu mendeskripsikan organ bicara.',
                        'learning_methods' => 'Collaborative Learning',
                        'assessment_criteria' => 'Tugas mandiri',
                        'weight' => 5,
                        'resources' => 'Roach (2009)',
                    ]
                ]
            ]
        ]);

        $response = $this->actingAs($user)
            ->post(route('rps.store'), [
                'course_id' => $course->id,
                'cpls' => [
                    [
                        'code' => 'P1',
                        'type' => 'Pengetahuan',
                        'description' => 'Menguasai konsep kebahasaan.',
                    ]
                ]
            ]);

        // Assert redirect to edit page
        $document = RpsDocument::first();
        $this->assertNotNull($document);
        $response->assertRedirect(route('rps.edit', $document->id));

        // Assert database records
        $this->assertDatabaseHas('rps_documents', [
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cpmks', [
            'course_id' => $course->id,
            'statement' => 'Mahasiswa mampu mengidentifikasi fonem bahasa Inggris.',
        ]);

        $this->assertDatabaseHas('rps_weeks', [
            'rps_document_id' => $document->id,
            'week_number' => 1,
            'sub_cpmk' => 'Mahasiswa mampu membedakan fonetik dan fonologi.',
        ]);

        RpsGeneratorAgent::assertPrompted(function ($prompt) use ($course) {
            return $prompt->contains($course->name);
        });
    }
}
