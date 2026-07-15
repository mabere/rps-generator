# Rencana Implementasi MVP

Dokumen ini menjadi panduan eksekusi pembangunan aplikasi Generator RPS Berbasis OBE berdasarkan [RDP Produk V1](RDP.md), [RDP Teknis](RDP%20Teknis.md), dan [Blueprint Database dan Route Map Final V1](blueprint%20database%20dan%20route%20map%20final%20untuk%20v1.md).

## 1. Prinsip Eksekusi

- Bangun fondasi data dan akses sebelum builder RPS.
- Selesaikan alur manual sebelum menambahkan AI.
- Setiap sprint menghasilkan bagian yang dapat diverifikasi.
- Gunakan migration, model, policy, request, action, controller, page, dan test yang saling mengikuti.
- Jangan menggabungkan state transition ke CRUD biasa.

## 2. Urutan Sprint

### Sprint 0: Persiapan dan Keputusan Final

Tujuan: memastikan fondasi implementasi tidak memiliki keputusan yang menggantung.

Output:

- Dokumen produk, teknis, database, dan route sudah diselaraskan.
- Enum domain final.
- Konvensi role dan status final.
- Daftar seed data pengembangan.

File yang ditinjau:

- `public/RDP.md`
- `public/RDP Teknis.md`
- `public/blueprint database dan route map final untuk v1.md`
- `routes/web.php`
- `config/ai.php`
- `config/auth.php`

Verifikasi:

- Tidak ada istilah status atau nama tabel yang berbeda antar dokumen.
- Dependensi AI dan authentication sudah terbaca dari konfigurasi proyek.

### Sprint 1: Auth, Role, dan Shell Aplikasi

Tujuan: menyediakan akses dasar dan kerangka navigasi aplikasi.

File backend:

- `app/Models/User.php`
- `app/Enums/UserRole.php`
- `app/Policies/*Policy.php`
- `app/Http/Middleware/*`
- `routes/web.php`

File frontend:

- `resources/js/layouts/app-layout.tsx`
- `resources/js/components/nav-main.tsx`
- `resources/js/pages/dashboard.tsx`
- `resources/js/types/auth.ts`

File test:

- `tests/Feature/Auth/*`
- `tests/Feature/AuthorizationTest.php`

Output:

- Admin, dosen, dan reviewer dapat login.
- Menu menyesuaikan role.
- Dashboard dasar dapat dibuka sesuai hak akses.

Verifikasi:

- User tanpa hak akses menerima response unauthorized/forbidden.
- Role user tersimpan dan dapat digunakan policy.

### Sprint 2: Master Program Studi dan Kurikulum

Tujuan: membangun struktur akademik induk.

File backend:

- `database/migrations/*create_study_programs_table.php`
- `database/migrations/*create_curricula_table.php`
- `app/Models/StudyProgram.php`
- `app/Models/Curriculum.php`
- `app/Http/Controllers/StudyProgramController.php`
- `app/Http/Controllers/CurriculumController.php`
- `app/Http/Requests/StudyProgram/*`
- `app/Http/Requests/Curriculum/*`
- `app/Policies/StudyProgramPolicy.php`
- `app/Policies/CurriculumPolicy.php`

File frontend:

- `resources/js/pages/study-programs/index.tsx`
- `resources/js/pages/study-programs/create.tsx`
- `resources/js/pages/study-programs/edit.tsx`
- `resources/js/pages/curricula/index.tsx`
- `resources/js/pages/curricula/create.tsx`
- `resources/js/pages/curricula/edit.tsx`

File test:

- `tests/Feature/StudyProgramTest.php`
- `tests/Feature/CurriculumTest.php`

Output:

- Admin dapat mengelola prodi dan kurikulum.
- Kode prodi unik.
- Kurikulum terhubung ke prodi.

### Sprint 3: Master Mata Kuliah dan CPL

Tujuan: menyediakan sumber data utama untuk penyusunan RPS.

File backend:

- `database/migrations/*create_courses_table.php`
- `database/migrations/*create_program_learning_outcomes_table.php`
- `app/Models/Course.php`
- `app/Models/ProgramLearningOutcome.php`
- `app/Http/Controllers/CourseController.php`
- `app/Http/Controllers/ProgramLearningOutcomeController.php`
- `app/Http/Requests/Course/*`
- `app/Http/Requests/ProgramLearningOutcome/*`
- `app/Policies/CoursePolicy.php`
- `app/Policies/ProgramLearningOutcomePolicy.php`

File frontend:

- `resources/js/pages/courses/index.tsx`
- `resources/js/pages/courses/create.tsx`
- `resources/js/pages/courses/edit.tsx`
- `resources/js/pages/program-learning-outcomes/index.tsx`
- `resources/js/pages/program-learning-outcomes/create.tsx`
- `resources/js/pages/program-learning-outcomes/edit.tsx`

File test:

- `tests/Feature/CourseTest.php`
- `tests/Feature/ProgramLearningOutcomeTest.php`

Output:

- Course terhubung ke prodi dan kurikulum.
- CPL terhubung ke prodi.
- Kode mata kuliah dan CPL tervalidasi sesuai lingkupnya.

### Sprint 4: Template RPS dan Data Uji

Tujuan: menyiapkan struktur template dan data pengembangan.

File backend:

- `database/migrations/*create_rps_templates_table.php`
- `app/Models/RpsTemplate.php`
- `app/Http/Controllers/RpsTemplateController.php`
- `app/Http/Requests/RpsTemplate/*`
- `app/Policies/RpsTemplatePolicy.php`
- `database/factories/*Factory.php`
- `database/seeders/DatabaseSeeder.php`

File frontend:

- `resources/js/pages/rps-templates/index.tsx`
- `resources/js/pages/rps-templates/create.tsx`
- `resources/js/pages/rps-templates/edit.tsx`

File test:

- `tests/Feature/RpsTemplateTest.php`
- `tests/Feature/DatabaseSeederTest.php`

Output:

- Admin dapat mengaktifkan template RPS.
- Seed data lokal tersedia untuk pengembangan builder.
- Template memiliki jumlah minggu default.

### Sprint 5: Dokumen RPS dan Builder Manual

Tujuan: memungkinkan dosen membuat RPS tanpa AI.

File backend:

- `database/migrations/*create_rps_documents_table.php`
- `database/migrations/*create_rps_document_selected_plos_table.php`
- `database/migrations/*create_rps_course_outcomes_table.php`
- `database/migrations/*create_rps_cpl_mappings_table.php`
- `database/migrations/*create_rps_weeks_table.php`
- `database/migrations/*create_rps_references_table.php`
- `app/Enums/RpsDocumentStatus.php`
- `app/Models/RpsDocument.php`
- `app/Models/RpsDocumentSelectedPlo.php`
- `app/Models/RpsCourseOutcome.php`
- `app/Models/RpsCplMapping.php`
- `app/Models/RpsWeek.php`
- `app/Models/RpsReference.php`
- `app/Actions/Rps/*`
- `app/Http/Controllers/RpsDocumentController.php`
- `app/Http/Controllers/RpsCourseOutcomeController.php`
- `app/Http/Controllers/RpsCplMappingController.php`
- `app/Http/Controllers/RpsWeekController.php`
- `app/Http/Controllers/RpsReferenceController.php`
- `app/Http/Requests/Rps/*`
- `app/Policies/RpsDocumentPolicy.php`

File frontend:

- `resources/js/pages/rps/index.tsx`
- `resources/js/pages/rps/create.tsx`
- `resources/js/pages/rps/show.tsx`
- `resources/js/pages/rps/edit.tsx`
- `resources/js/pages/rps/preview.tsx`
- `resources/js/components/rps-builder-stepper.tsx`
- `resources/js/components/rps-basic-information-form.tsx`
- `resources/js/components/rps-selected-plos-form.tsx`
- `resources/js/components/rps-course-outcome-editor.tsx`
- `resources/js/components/rps-mapping-matrix.tsx`
- `resources/js/components/rps-weekly-plan-editor.tsx`
- `resources/js/components/rps-reference-editor.tsx`

File test:

- `tests/Feature/RpsDocumentTest.php`
- `tests/Feature/RpsBuilderTest.php`
- `tests/Feature/RpsDocumentPolicyTest.php`

Output:

- Dosen dapat membuat draft.
- Data master disalin menjadi snapshot dokumen.
- Setiap step dapat disimpan terpisah.
- Draft dapat dipreview tanpa AI.

### Sprint 6: Review dan Approval

Tujuan: menyelesaikan lifecycle dokumen RPS.

File backend:

- `database/migrations/*create_rps_reviews_table.php`
- `app/Models/RpsReview.php`
- `app/Actions/Rps/SubmitRpsForReview.php`
- `app/Actions/Rps/ReturnRpsToDraft.php`
- `app/Actions/Rps/ApproveRpsDocument.php`
- `app/Actions/Rps/CreateRpsRevision.php`
- `app/Http/Controllers/RpsWorkflowController.php`
- `app/Http/Controllers/RpsReviewController.php`
- `app/Http/Requests/Rps/SubmitRpsReviewRequest.php`
- `app/Http/Requests/Rps/ApproveRpsDocumentRequest.php`
- `app/Http/Requests/Rps/ReturnRpsDocumentToDraftRequest.php`
- `app/Http/Requests/Rps/CreateRpsRevisionRequest.php`
- `app/Policies/RpsReviewPolicy.php`

File frontend:

- `resources/js/pages/reviews/index.tsx`
- `resources/js/pages/reviews/show.tsx`
- `resources/js/components/rps-status-badge.tsx`
- `resources/js/components/review-notes-panel.tsx`

File test:

- `tests/Feature/RpsWorkflowTest.php`
- `tests/Feature/RpsReviewTest.php`
- `tests/Feature/RpsRevisionTest.php`

Output:

- Draft dapat dikirim ke review.
- Reviewer dapat mengembalikan atau approve.
- Approved document read-only.
- Revisi dibuat melalui clone ke draft baru.

### Sprint 7: AI Assistance

Tujuan: menambahkan bantuan AI setelah workflow manual stabil.

File backend:

- `database/migrations/*create_ai_generation_logs_table.php`
- `app/Models/AiGenerationLog.php`
- `app/Enums/AiFeatureType.php`
- `app/Ai/Agents/SuggestCourseOutcomesAgent.php`
- `app/Ai/Agents/SuggestCplMappingsAgent.php`
- `app/Ai/Agents/GenerateWeeklyPlanAgent.php`
- `app/Ai/Agents/ReviewRpsAlignmentAgent.php`
- `app/Ai/Agents/RewriteAcademicNarrativeAgent.php`
- `app/Actions/RpsAi/*`
- `app/Http/Controllers/RpsAiAssistantController.php`

File frontend:

- `resources/js/components/ai-suggestion-panel.tsx`
- `resources/js/components/rps-alignment-panel.tsx`
- `resources/js/components/ai-generation-status.tsx`

File test:

- `tests/Feature/RpsAiAssistantTest.php`
- `tests/Unit/Ai/*`

Output:

- AI mengembalikan structured output.
- User dapat meninjau dan menerapkan saran.
- Setiap pemanggilan AI tercatat.
- AI tidak menimpa isi final secara otomatis.

### Sprint 8: Export dan Hardening

Tujuan: menyiapkan hasil yang dapat digunakan dan memperkuat kualitas aplikasi.

File backend:

- `app/Http/Controllers/RpsExportController.php`
- `resources/views/rps/print.blade.php`
- `app/Actions/Rps/ExportRpsPdf.php`

File frontend:

- `resources/js/components/rps-export-actions.tsx`

File test:

- `tests/Feature/RpsExportTest.php`
- `tests/Feature/RpsAccessRegressionTest.php`

Output:

- RPS dapat diexport ke PDF.
- Preview dan hasil print konsisten.
- Regression test utama tersedia.

## 3. Dependensi Antar Sprint

- Sprint 1 menjadi prasyarat seluruh modul terproteksi.
- Sprint 2 dan 3 menjadi prasyarat pembuatan draft RPS.
- Sprint 4 menjadi prasyarat builder yang memakai template.
- Sprint 5 harus selesai sebelum review dan AI.
- Sprint 6 harus stabil sebelum AI menghasilkan perubahan pada dokumen.
- Sprint 7 membutuhkan konfigurasi provider AI yang valid.
- Sprint 8 dilakukan setelah struktur preview stabil.

## 4. Urutan Verifikasi

Setiap sprint minimal diverifikasi dengan:

1. Migration dan model dapat digunakan.
2. Policy menolak akses yang tidak sah.
3. Happy path fitur berjalan.
4. Failure path utama tervalidasi.
5. Feature test terkait lulus.
6. Frontend build tetap berhasil setelah perubahan frontend.

## 5. Definition of Done MVP

MVP dianggap siap untuk uji pengguna jika:

- Admin dapat menyiapkan prodi, kurikulum, mata kuliah, CPL, dan template.
- Dosen dapat membuat serta menyimpan draft RPS manual.
- Dosen dapat memilih CPL, menyusun CPMK, mapping, minggu, dan referensi.
- Reviewer dapat mengembalikan atau menyetujui RPS.
- Approved document tidak dapat diedit langsung.
- Revisi menghasilkan dokumen draft baru.
- AI dapat memberi saran terstruktur dan hasilnya dapat diterapkan secara manual.
- RPS dapat dipreview dan diexport ke PDF.
- Test utama untuk authorization, workflow, validation, AI, dan export lulus.
