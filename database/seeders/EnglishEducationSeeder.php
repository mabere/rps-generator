<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyProgram;
use App\Models\Cpl;
use App\Models\Course;

class EnglishEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = StudyProgram::firstOrCreate(
            ['code' => 'PBI'],
            [
                'name' => 'Pendidikan Bahasa Inggris',
                'description' => 'Program Studi Pendidikan Bahasa Inggris S1',
            ]
        );

        // Seed CPLs
        $cpls = [
            // Sikap (S)
            ['code' => 'S1', 'description' => 'Bertakwa kepada Tuhan Yang Maha Esa, berakhlak mulia, dan menjunjung nilai kemanusiaan dalam menjalankan tugas sebagai pendidik Bahasa Inggris.', 'type' => 'Sikap'],
            ['code' => 'S2', 'description' => 'Menunjukkan sikap nasionalisme, cinta tanah air, dan penghargaan terhadap keberagaman budaya dalam pembelajaran Bahasa Inggris.', 'type' => 'Sikap'],
            ['code' => 'S3', 'description' => 'Bertanggung jawab, disiplin, dan menjunjung etika akademik serta etika profesi guru.', 'type' => 'Sikap'],
            ['code' => 'S4', 'description' => 'Bersikap reflektif, terbuka terhadap kritik, dan memiliki komitmen terhadap pengembangan diri berkelanjutan.', 'type' => 'Sikap'],
            ['code' => 'S5', 'description' => 'Menunjukkan sikap inklusif, empatik, dan menghargai perbedaan latar belakang peserta didik.', 'type' => 'Sikap'],

            // Pengetahuan (P)
            ['code' => 'P1', 'description' => 'Menguasai konsep kebahasaan Bahasa Inggris (phonology, morphology, syntax, semantics, pragmatics).', 'type' => 'Pengetahuan'],
            ['code' => 'P2', 'description' => 'Menguasai teori pemerolehan bahasa dan pembelajaran Bahasa Inggris sebagai bahasa asing (EFL).', 'type' => 'Pengetahuan'],
            ['code' => 'P3', 'description' => 'Menguasai teori dan pendekatan pengajaran Bahasa Inggris (methods, approaches, strategies).', 'type' => 'Pengetahuan'],
            ['code' => 'P4', 'description' => 'Menguasai konsep penilaian pembelajaran Bahasa Inggris yang autentik dan berorientasi kompetensi.', 'type' => 'Pengetahuan'],
            ['code' => 'P5', 'description' => 'Menguasai dasar-dasar penelitian pendidikan Bahasa Inggris dan literasi akademik.', 'type' => 'Pengetahuan'],
            ['code' => 'P6', 'description' => 'Menguasai pemanfaatan teknologi dan media digital dalam pembelajaran Bahasa Inggris.', 'type' => 'Pengetahuan'],

            // Keterampilan Umum (KU)
            ['code' => 'KU1', 'description' => 'Mampu berpikir kritis, logis, dan sistematis dalam menyelesaikan permasalahan pembelajaran Bahasa Inggris.', 'type' => 'Keterampilan Umum'],
            ['code' => 'KU2', 'description' => 'Mampu berkomunikasi efektif secara lisan dan tulisan dalam Bahasa Inggris dan Bahasa Indonesia.', 'type' => 'Keterampilan Umum'],
            ['code' => 'KU3', 'description' => 'Mampu memanfaatkan teknologi informasi dan komunikasi untuk pembelajaran dan pengembangan profesional.', 'type' => 'Keterampilan Umum'],
            ['code' => 'KU4', 'description' => 'Mampu bekerja secara mandiri maupun kolaboratif dalam konteks akademik dan profesional.', 'type' => 'Keterampilan Umum'],
            ['code' => 'KU5', 'description' => 'Mampu menyusun karya ilmiah sederhana di bidang pendidikan Bahasa Inggris sesuai kaidah akademik.', 'type' => 'Keterampilan Umum'],

            // Keterampilan Khusus (KK)
            ['code' => 'KK1', 'description' => 'Mampu merancang perangkat pembelajaran Bahasa Inggris (RPP/Modul Ajar) sesuai kurikulum dan karakteristik peserta didik.', 'type' => 'Keterampilan Khusus'],
            ['code' => 'KK2', 'description' => 'Mampu melaksanakan pembelajaran Bahasa Inggris yang komunikatif, interaktif, dan berpusat pada peserta didik.', 'type' => 'Keterampilan Khusus'],
            ['code' => 'KK3', 'description' => 'Mampu menilai dan mengevaluasi hasil belajar Bahasa Inggris secara autentik dan berkelanjutan.', 'type' => 'Keterampilan Khusus'],
            ['code' => 'KK4', 'description' => 'Mampu menggunakan Bahasa Inggris secara fungsional dalam konteks akademik dan pembelajaran.', 'type' => 'Keterampilan Khusus'],
            ['code' => 'KK5', 'description' => 'Mampu melakukan refleksi dan perbaikan pembelajaran Bahasa Inggris berbasis data dan hasil evaluasi.', 'type' => 'Keterampilan Khusus'],
            ['code' => 'KK6', 'description' => 'Mampu melaksanakan penelitian sederhana untuk meningkatkan kualitas pembelajaran Bahasa Inggris.', 'type' => 'Keterampilan Khusus'],
        ];

        foreach ($cpls as $cplData) {
            $program->cpls()->firstOrCreate(['code' => $cplData['code']], $cplData);
        }

        // Seed some example courses
        $courses = [
            [
                'code' => 'ENG201',
                'name' => 'English Phonology',
                'sks' => 2,
                'semester' => 3,
                'description' => 'This course introduces students to the sound system of English, covering phonetics and phonology, vowel/consonant classification, stress, and intonation.',
            ],
            [
                'code' => 'ENG302',
                'name' => 'EFL Methodology',
                'sks' => 3,
                'semester' => 5,
                'description' => 'Students will learn various approaches, methods, and techniques for teaching English as a Foreign Language, with a focus on communicative language teaching and student-centered approaches.',
            ],
            [
                'code' => 'ENG305',
                'name' => 'Language Assessment',
                'sks' => 3,
                'semester' => 6,
                'description' => 'This course covers the principles of language testing and assessment, authentic assessment design, and evaluating student performance in EFL classrooms.',
            ],
        ];

        foreach ($courses as $courseData) {
            $course = $program->courses()->firstOrCreate(['code' => $courseData['code']], $courseData);
            
            // Map some CPLs to courses by default
            if ($courseData['code'] === 'ENG201') {
                $cplIds = $program->cpls()->whereIn('code', ['S3', 'P1', 'KU1', 'KK4'])->pluck('id');
                $course->cpls()->sync($cplIds);
            } elseif ($courseData['code'] === 'ENG302') {
                $cplIds = $program->cpls()->whereIn('code', ['S3', 'P2', 'P3', 'KU4', 'KK1', 'KK2'])->pluck('id');
                $course->cpls()->sync($cplIds);
            } elseif ($courseData['code'] === 'ENG305') {
                $cplIds = $program->cpls()->whereIn('code', ['S3', 'P4', 'KU1', 'KK3', 'KK5'])->pluck('id');
                $course->cpls()->sync($cplIds);
            }
        }
    }
}
