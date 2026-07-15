# RDP Teknis
## Spesifikasi Implementasi Laravel 13 + Inertia React

Dokumen ini merangkum spesifikasi implementasi teknis untuk aplikasi Generator RPS Berbasis OBE berdasarkan keputusan produk v1.

## 1. Tujuan Teknis
Tujuan implementasi teknis v1:

- Stabil untuk pengelolaan master data akademik.
- Nyaman untuk penyusunan RPS bertahap.
- Mendukung AI assist secara terstruktur.
- Aman untuk review, approval, dan arsip snapshot.
- Tetap sederhana tanpa over-engineering.

Arah teknis:

- Laravel 13 untuk backend, domain, validation, auth, dan lifecycle dokumen.
- Inertia React v3 untuk pengalaman SPA tanpa memisahkan backend dan frontend.
- Wayfinder untuk route typing frontend.
- Fortify untuk authentication.
- Laravel AI SDK untuk fitur AI terstruktur.
- SQLite untuk fase awal pengembangan lokal, dengan desain yang tetap aman bila nanti pindah DB lain.

## 2. Arsitektur Aplikasi
Lapisan arsitektur yang disarankan:

- `AuthAndAccessLayer`
- `AcademicMasterDataLayer`
- `RpsDocumentLayer`
- `AiAssistanceLayer`
- `ReviewAndApprovalLayer`
- `PresentationLayer`

Pemetaan implementasi Laravel:

- `app/Models` untuk entitas inti.
- `app/Http/Controllers` untuk entry point per modul.
- `app/Http/Requests` untuk validasi input.
- `app/Policies` untuk otorisasi dokumen dan master data.
- `app/Actions` atau `app/Services` untuk use case bisnis.
- `app/Ai/Agents` untuk agent Laravel AI SDK.
- `resources/js/pages` untuk halaman Inertia React.
- `resources/js/components` untuk komponen UI reusable.
- `resources/js/layouts` untuk shell admin, dosen, dan reviewer.

## 3. Struktur Modul Backend

### Auth
- Fortify login, register, reset password, dan verification.
- Role check sederhana.
- Policy-based authorization.

### Master Data
- Study Program.
- Curriculum.
- Course.
- Program Learning Outcome.
- RPS Template.
- User Management.

### RPS
- Create draft.
- Update draft.
- CPL selection.
- CPMK management.
- CPL-CPMK mapping.
- Weekly plan management.
- References.
- Preview.
- Submit review.
- Create revision.

### Review
- List pending reviews.
- Review detail.
- Return to draft.
- Approve.

### AI
- Suggest course outcomes.
- Suggest mappings.
- Generate weekly plan.
- Review alignment.
- Rewrite academic text.

### Audit and Tracking
- Review history.
- AI generation logs.
- Revision relationship.

## 4. Struktur Model yang Disarankan
Model inti Laravel:

- `User`
- `StudyProgram`
- `Curriculum`
- `Course`
- `ProgramLearningOutcome`
- `RpsTemplate`
- `RpsDocument`
- `RpsDocumentSelectedPlo`
- `RpsCourseOutcome`
- `RpsCplMapping`
- `RpsWeek`
- `RpsReference`
- `RpsReview`
- `AiGenerationLog`

Keputusan revisi:

- Tidak perlu model `RpsDocumentRevision` terpisah pada MVP.
- Gunakan `parent_document_id` nullable pada `rps_documents`.

## 5. Struktur Database Konseptual
Skema teknis inti:

### `users`
- `id`
- `name`
- `email`
- `password`
- `role`
- `is_active`
- `timestamps`

### `study_programs`
- `id`
- `name`
- `code`
- `degree_level`
- `department_name`
- `faculty_name`
- `is_active`
- `timestamps`

### `curricula`
- `id`
- `study_program_id`
- `name`
- `code`
- `academic_year`
- `description`
- `is_active`
- `timestamps`

### `courses`
- `id`
- `study_program_id`
- `curriculum_id`
- `code`
- `name`
- `credit_units`
- `semester`
- `course_type`
- `description`
- `is_active`
- `timestamps`

### `program_learning_outcomes`
- `id`
- `study_program_id`
- `code`
- `statement`
- `category`
- `sequence`
- `is_active`
- `timestamps`

### `rps_templates`
- `id`
- `name`
- `code`
- `version`
- `description`
- `default_weeks_count`
- `layout_key`
- `is_active`
- `timestamps`

### `rps_documents`
- `id`
- `parent_document_id`
- `study_program_id`
- `curriculum_id`
- `course_id`
- `rps_template_id`
- `created_by`
- `reviewed_by`
- `title`
- `semester_label`
- `academic_year`
- `lecturer_name`
- `coordinator_name`
- `course_code_snapshot`
- `course_name_snapshot`
- `credit_units_snapshot`
- `study_program_name_snapshot`
- `curriculum_name_snapshot`
- `template_name_snapshot`
- `course_description`
- `learning_materials_overview`
- `assessment_overview`
- `status`
- `submitted_at`
- `approved_at`
- `review_notes_summary`
- `timestamps`

### `rps_document_selected_plos`
- `id`
- `rps_document_id`
- `program_learning_outcome_id`
- `plo_code_snapshot`
- `plo_statement_snapshot`
- `sequence`
- `timestamps`

### `rps_course_outcomes`
- `id`
- `rps_document_id`
- `code`
- `statement`
- `indicator`
- `taxonomy_level`
- `sequence`
- `timestamps`

### `rps_cpl_mappings`
- `id`
- `rps_document_id`
- `rps_course_outcome_id`
- `program_learning_outcome_id`
- `notes`
- `alignment_strength`
- `timestamps`

### `rps_weeks`
- `id`
- `rps_document_id`
- `week_number`
- `sub_course_outcome`
- `learning_material`
- `learning_method`
- `student_learning_experience`
- `assessment_indicator`
- `assessment_criteria`
- `assessment_form`
- `assessment_weight`
- `references_text`
- `sequence`
- `timestamps`

### `rps_references`
- `id`
- `rps_document_id`
- `reference_type`
- `citation_text`
- `is_primary`
- `sequence`
- `timestamps`

### `rps_reviews`
- `id`
- `rps_document_id`
- `reviewer_id`
- `status_after_review`
- `notes`
- `reviewed_at`
- `timestamps`

### `ai_generation_logs`
- `id`
- `rps_document_id`
- `user_id`
- `feature_type`
- `input_payload`
- `output_payload`
- `is_applied`
- `generated_at`
- `timestamps`

## 6. Snapshot Strategy Teknis
Snapshot dilakukan pada:

### `rps_documents`
- Nama prodi.
- Nama kurikulum.
- Kode dan nama mata kuliah.
- SKS.
- Nama template.

### `rps_document_selected_plos`
- Kode CPL.
- Pernyataan CPL.

Tujuannya:

- Perubahan master tidak mengganggu dokumen lama.
- Preview dan export stabil.
- Arsip approval aman.

## 7. Enum dan Konstanta Domain
Enum yang disarankan:

- `UserRole`
  - `Admin`
  - `Lecturer`
  - `Reviewer`
- `RpsDocumentStatus`
  - `Draft`
  - `InReview`
  - `Approved`
- `ReferenceType`
  - `Primary`
  - `Supporting`
- `AiFeatureType`
  - `CourseOutcomeSuggestion`
  - `CplMappingSuggestion`
  - `WeeklyPlanGeneration`
  - `AlignmentReview`
  - `NarrativeRewrite`
- `PloCategory`
  - `Sikap`
  - `Pengetahuan`
  - `KeterampilanUmum`
  - `KeterampilanKhusus`

## 8. Policies dan Authorization
Policies minimal:

- `StudyProgramPolicy`
- `CurriculumPolicy`
- `CoursePolicy`
- `ProgramLearningOutcomePolicy`
- `RpsTemplatePolicy`
- `RpsDocumentPolicy`
- `RpsReviewPolicy`

Aturan utama:

- Admin mengelola semua master.
- Dosen create dan update draft miliknya sendiri.
- Reviewer mereview dokumen `in_review`.
- Dokumen approved tidak bisa diupdate biasa.
- Create revision hanya untuk user yang berwenang.

## 9. Form Request dan Validasi
Request yang disarankan:

### Master data
- `StoreStudyProgramRequest`
- `UpdateStudyProgramRequest`
- `StoreCurriculumRequest`
- `StoreCourseRequest`
- `StoreProgramLearningOutcomeRequest`
- `StoreRpsTemplateRequest`

### RPS
- `StoreRpsDocumentRequest`
- `UpdateRpsBasicInformationRequest`
- `SyncRpsSelectedPlosRequest`
- `StoreRpsCourseOutcomeRequest`
- `UpdateRpsCourseOutcomeRequest`
- `SyncRpsCplMappingsRequest`
- `StoreRpsWeekRequest`
- `UpdateRpsWeekRequest`
- `StoreRpsReferenceRequest`
- `SubmitRpsReviewRequest`
- `ApproveRpsDocumentRequest`
- `ReturnRpsDocumentToDraftRequest`
- `CreateRpsRevisionRequest`

Prinsip validasi:

- Validasi form per step.
- Validasi domain akhir saat submit review.
- Pisahkan inline validation dari submission validation.

## 10. Service dan Action Layer
Use case utama yang disarankan:

- `CreateRpsDraft`
- `UpdateRpsBasicInformation`
- `SyncSelectedProgramLearningOutcomes`
- `StoreCourseOutcome`
- `SyncCourseOutcomeMappings`
- `GenerateRpsWeeklyPlan`
- `SubmitRpsForReview`
- `ReturnRpsToDraft`
- `ApproveRpsDocument`
- `CreateRpsRevision`

AI actions:

- `SuggestCourseOutcomes`
- `SuggestCplMappings`
- `GenerateWeeklyPlanFromAi`
- `ReviewRpsAlignment`
- `RewriteAcademicNarrative`

## 11. Rancangan Controller
Controller yang disarankan:

### Master data
- `StudyProgramController`
- `CurriculumController`
- `CourseController`
- `ProgramLearningOutcomeController`
- `RpsTemplateController`
- `UserController`

### RPS
- `RpsDocumentController`
- `RpsCourseOutcomeController`
- `RpsCplMappingController`
- `RpsWeekController`
- `RpsReferenceController`
- `RpsWorkflowController`

### Review
- `RpsReviewController`

### AI
- `RpsAiAssistantController`

Catatan:

- State transition dipusatkan di `RpsWorkflowController`.
- Endpoint AI cukup memakai satu controller pada MVP.

## 12. Routing Strategy
Kelompok route:

- Authenticated routes.
- Admin routes.
- Lecturer routes.
- Reviewer routes.
- Shared RPS routes berbasis policy.

Contoh area:

- `/dashboard`
- `/study-programs`
- `/curricula`
- `/courses`
- `/program-learning-outcomes`
- `/rps-templates`
- `/users`
- `/rps`
- `/reviews`

## 13. Struktur Halaman Inertia React
Struktur `resources/js/pages` yang disarankan:

### Shared
- `dashboard.tsx`

### Master data
- `study-programs/index.tsx`
- `study-programs/create.tsx`
- `study-programs/edit.tsx`
- `curricula/index.tsx`
- `curricula/create.tsx`
- `curricula/edit.tsx`
- `courses/index.tsx`
- `courses/create.tsx`
- `courses/edit.tsx`
- `program-learning-outcomes/index.tsx`
- `program-learning-outcomes/create.tsx`
- `program-learning-outcomes/edit.tsx`
- `rps-templates/index.tsx`
- `rps-templates/create.tsx`
- `rps-templates/edit.tsx`
- `users/index.tsx`
- `users/create.tsx`
- `users/edit.tsx`

### RPS
- `rps/index.tsx`
- `rps/create.tsx`
- `rps/show.tsx`
- `rps/edit.tsx`
- `rps/preview.tsx`

### Review
- `reviews/index.tsx`
- `reviews/show.tsx`

Keputusan builder:

- Gunakan satu page builder `rps/edit.tsx` dengan stepper internal.

## 14. Layout Frontend
Layout yang disarankan:

- `app-layout`
- `admin-layout`
- `rps-builder-layout`

Catatan:

- Ikuti pola layout repo yang sudah ada.
- Jangan membuat sistem layout baru yang tidak perlu.

## 15. Komponen React yang Disarankan
Komponen utama:

- `rps-builder-stepper`
- `rps-basic-information-form`
- `rps-selected-plos-form`
- `rps-course-outcome-editor`
- `rps-mapping-matrix`
- `rps-weekly-plan-editor`
- `rps-reference-editor`
- `rps-alignment-panel`
- `rps-status-badge`
- `review-notes-panel`
- `ai-suggestion-panel`

Komponen UX:

- `autosave-indicator`
- `validation-summary`
- `warning-banner`
- `review-notes-panel`

## 16. State Management Frontend
Strategi state:

- Gunakan `useForm` untuk submission per section.
- Gunakan state lokal React untuk wizard step.
- Gunakan props server untuk data master dan draft.
- Gunakan partial reload atau visit seperlunya.

Prinsip:

- Hindari global store berat pada MVP.

## 17. Strategi Builder
Opsi yang dipilih:

- Satu page builder.
- Save per step ke backend.

Urutan save:

- Step 1 simpan basic information.
- Step 2 simpan selected CPL.
- Step 3 simpan CPMK.
- Step 4 simpan mappings.
- Step 5 simpan weeks.
- Step 6 simpan references dan assessment overview.
- Submit review menjalankan validasi global.

## 18. Strategi Laravel AI SDK
AI diimplementasikan lewat `Agent` classes dengan structured output.

Agent yang direkomendasikan:

- `SuggestCourseOutcomesAgent`
- `SuggestCplMappingsAgent`
- `GenerateWeeklyPlanAgent`
- `ReviewRpsAlignmentAgent`
- `RewriteAcademicNarrativeAgent`

Alasan:

- Lebih aman daripada free text.
- Mudah divalidasi.
- Mudah ditampilkan di UI.
- Mudah disimpan ke `ai_generation_logs`.

## 19. Bentuk Output AI
Structured output yang disarankan:

### `SuggestCourseOutcomesAgent`
- `outcomes[]`
  - `statement`
  - `rationale`
  - `taxonomy_level`

### `SuggestCplMappingsAgent`
- `mappings[]`
  - `outcome_index` atau `outcome_code`
  - `plo_code`
  - `rationale`
  - `confidence`

### `GenerateWeeklyPlanAgent`
- `weeks[]`
  - `week_number`
  - `sub_course_outcome`
  - `learning_material`
  - `learning_method`
  - `student_learning_experience`
  - `assessment_form`
  - `assessment_indicator`
  - `suggested_weight`
  - `references_note`

### `ReviewRpsAlignmentAgent`
- `errors[]`
- `warnings[]`
- `suggestions[]`
- `summary_score`

### `RewriteAcademicNarrativeAgent`
- `rewritten_text`
- `notes`

## 20. AI Prompting Strategy
Prompt AI harus berbasis data terstruktur.

Input umum agent:

- Data mata kuliah.
- CPL terpilih.
- CPMK eksisting.
- Jumlah minggu.
- Ekspektasi template.
- Constraint akademik.

Prinsip:

- Prompt sempit.
- Output terstruktur.
- Pengguna tetap memilih hasil akhir.

## 21. Penyimpanan dan Audit AI
Setiap pemanggilan AI sebaiknya menyimpan:

- Input ringkas.
- Output penuh.
- Feature type.
- Pengguna pemicu.
- Status apakah hasil diterapkan ke draft.

## 22. Review dan Approval Teknis
Approval tidak dilakukan lewat update status biasa pada CRUD.

Aksi khusus:

- `submitForReview`
- `returnToDraft`
- `approve`
- `createRevision`

Tujuan:

- Menjaga state transition domain tetap eksplisit.

## 23. Export Strategy
Untuk MVP:

- Preview HTML Inertia.
- Export PDF dari tampilan terstruktur yang sama atau view print khusus.

Belum perlu:

- DOCX.

## 24. Search, Filter, dan Listing
Filter penting:

### RPS list
- Status.
- Prodi.
- Kurikulum.
- Mata kuliah.
- Periode akademik.
- Dosen.

### Review list
- Pending review.
- Approved.
- Returned.

### Master data list
- Active atau inactive.
- Search by code atau name.

## 25. Database Constraints yang Disarankan
Constraint teknis utama:

- Unique study program code.
- Unique template code.
- Unique PLO code per study program.
- Unique curriculum code per study program.
- Unique mapping pair pada `rps_cpl_mappings`.
- Unique selected PLO per document.
- Unique week number per document.

Catatan `rps_documents`:

- Jangan terlalu kaku di DB untuk draft.
- Duplicate final RPS lebih aman dikontrol lewat business rule saat submit atau approve.

## 26. Testing Strategy
Kelompok test:

### Feature tests
- Auth and access.
- Admin master CRUD.
- Dosen create draft.
- Save step builder.
- Submit review.
- Reviewer return to draft.
- Reviewer approve.
- Create revision.
- Policy enforcement.

### AI-related tests
- Fake agent response.
- Structured output consumption.
- AI log creation.
- Apply suggestion flow.

### Validation tests
- Cannot submit incomplete RPS.
- Cannot edit approved document.
- Cannot approve own document.
- Cannot map CPMK to foreign PLO.

## 27. Factory dan Seeder Strategy
Factories:

- `UserFactory`
- `StudyProgramFactory`
- `CurriculumFactory`
- `CourseFactory`
- `ProgramLearningOutcomeFactory`
- `RpsTemplateFactory`
- `RpsDocumentFactory`

Seeders awal:

- Admin user.
- Sample dosen.
- Sample reviewer.
- Satu prodi.
- Satu kurikulum.
- Beberapa mata kuliah.
- Beberapa CPL.
- Satu template RPS.

## 28. Sprint Implementasi yang Disarankan

### Sprint 1: Fondasi
- Auth.
- Role.
- Policies dasar.
- Dashboard dasar.
- Master Study Program, Curriculum, dan Course.

### Sprint 2: Master Akademik
- PLO CRUD.
- Template CRUD.
- User management sederhana.
- Filter listing.

### Sprint 3: RPS Manual Builder
- Create draft.
- Step 1 sampai Step 6.
- Selected PLO.
- CPMK.
- Mapping.
- Weeks.
- References.
- Preview dasar.

### Sprint 4: Review Workflow
- Submit review.
- Review list.
- Return to draft.
- Approve.
- Create revision.
- Status badge dan audit dasar.

### Sprint 5: AI Assistance
- AI agents.
- Suggestion UI.
- Apply suggestion flow.
- Alignment review.
- AI logs.

Tahap penutup:

- Export PDF.
- Hardening.
- Test completion.

## 29. Risiko Teknis yang Perlu Dijaga
Risiko utama:

- Builder menjadi terlalu besar dan sulit dipelihara.
- Snapshot tidak lengkap sehingga arsip rapuh.
- AI output terlalu bebas jika schema tidak ketat.
- Route atau controller terlalu CRUD-oriented untuk state domain.
- Validasi tersebar dan tidak konsisten antara step dan submit final.

## 30. Keputusan Teknis Final
Fondasi implementasi yang direkomendasikan:

- Gunakan model snapshot pada `rps_documents`.
- Tambahkan tabel `rps_document_selected_plos`.
- Gunakan enum untuk role, status, dan feature type.
- Gunakan policy untuk semua akses penting.
- Pisahkan use case ke action atau service.
- Gunakan wizard builder satu page dengan save per step.
- Implementasikan AI lewat Laravel AI SDK agents dengan structured output.
- Simpan semua jejak AI di log.
- Lakukan approval lewat domain actions, bukan update status biasa.
- Dokumen approved selalu read-only dan revisi dilakukan via clone.
