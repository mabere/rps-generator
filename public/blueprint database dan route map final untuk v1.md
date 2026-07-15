# Blueprint Database dan Route Map Final V1

Dokumen ini adalah spesifikasi operasional database, route, controller, dan page map untuk implementasi v1.

## 1. Prinsip Struktur
Struktur final mengikuti keputusan produk dan teknis yang sudah dikunci:

- Master data tetap sederhana dan resmi.
- Dokumen RPS bersifat snapshot.
- Workflow status eksplisit.
- AI hanya memberi saran terstruktur.
- Dokumen approved tidak diedit langsung.

## 2. Daftar Tabel Final
Tabel final v1:

- `users`
- `study_programs`
- `curricula`
- `courses`
- `program_learning_outcomes`
- `rps_templates`
- `rps_documents`
- `rps_document_selected_plos`
- `rps_course_outcomes`
- `rps_cpl_mappings`
- `rps_weeks`
- `rps_references`
- `rps_reviews`
- `ai_generation_logs`

## 3. Blueprint Tabel

### `users`
Kolom:

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `role`
- `is_active`
- `remember_token`
- `created_at`
- `updated_at`

Index:

- unique `email`
- index `role`
- index `is_active`

Catatan:

- `role`: `admin`, `lecturer`, `reviewer`

### `study_programs`
Kolom:

- `id`
- `name`
- `code`
- `degree_level`
- `department_name`
- `faculty_name`
- `is_active`
- `created_at`
- `updated_at`

Index:

- unique `code`
- index `is_active`

### `curricula`
Kolom:

- `id`
- `study_program_id`
- `name`
- `code`
- `academic_year`
- `description`
- `is_active`
- `created_at`
- `updated_at`

Foreign key:

- `study_program_id -> study_programs.id`

Index:

- unique `study_program_id + code`
- index `study_program_id`
- index `is_active`

### `courses`
Kolom:

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
- `created_at`
- `updated_at`

Foreign key:

- `study_program_id -> study_programs.id`
- `curriculum_id -> curricula.id`

Index:

- unique `curriculum_id + code`
- index `study_program_id`
- index `curriculum_id`
- index `semester`
- index `is_active`

### `program_learning_outcomes`
Kolom:

- `id`
- `study_program_id`
- `code`
- `statement`
- `category`
- `sequence`
- `is_active`
- `created_at`
- `updated_at`

Foreign key:

- `study_program_id -> study_programs.id`

Index:

- unique `study_program_id + code`
- index `study_program_id`
- index `category`
- index `sequence`
- index `is_active`

### `rps_templates`
Kolom:

- `id`
- `name`
- `code`
- `version`
- `description`
- `default_weeks_count`
- `layout_key`
- `is_active`
- `created_at`
- `updated_at`

Index:

- unique `code`
- index `is_active`

### `rps_documents`
Kolom:

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
- `created_at`
- `updated_at`

Foreign key:

- `parent_document_id -> rps_documents.id`
- `study_program_id -> study_programs.id`
- `curriculum_id -> curricula.id`
- `course_id -> courses.id`
- `rps_template_id -> rps_templates.id`
- `created_by -> users.id`
- `reviewed_by -> users.id`

Index:

- index `parent_document_id`
- index `study_program_id`
- index `curriculum_id`
- index `course_id`
- index `rps_template_id`
- index `created_by`
- index `reviewed_by`
- index `status`
- index `academic_year`
- index `semester_label`
- composite index `course_id + academic_year + semester_label`
- composite index `created_by + status`

Catatan:

- Unique draft tidak dipaksa di level DB.
- Duplicate final RPS lebih aman dibatasi lewat business rule.

### `rps_document_selected_plos`
Kolom:

- `id`
- `rps_document_id`
- `program_learning_outcome_id`
- `plo_code_snapshot`
- `plo_statement_snapshot`
- `sequence`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`
- `program_learning_outcome_id -> program_learning_outcomes.id`

Index:

- unique `rps_document_id + program_learning_outcome_id`
- index `rps_document_id`
- index `program_learning_outcome_id`
- index `sequence`

### `rps_course_outcomes`
Kolom:

- `id`
- `rps_document_id`
- `code`
- `statement`
- `indicator`
- `taxonomy_level`
- `sequence`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`

Index:

- index `rps_document_id`
- index `sequence`

### `rps_cpl_mappings`
Kolom:

- `id`
- `rps_document_id`
- `rps_course_outcome_id`
- `program_learning_outcome_id`
- `notes`
- `alignment_strength`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`
- `rps_course_outcome_id -> rps_course_outcomes.id`
- `program_learning_outcome_id -> program_learning_outcomes.id`

Index:

- unique `rps_course_outcome_id + program_learning_outcome_id`
- index `rps_document_id`
- index `rps_course_outcome_id`
- index `program_learning_outcome_id`

### `rps_weeks`
Kolom:

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
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`

Index:

- unique `rps_document_id + week_number`
- index `rps_document_id`
- index `sequence`

### `rps_references`
Kolom:

- `id`
- `rps_document_id`
- `reference_type`
- `citation_text`
- `is_primary`
- `sequence`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`

Index:

- index `rps_document_id`
- index `reference_type`
- index `is_primary`
- index `sequence`

### `rps_reviews`
Kolom:

- `id`
- `rps_document_id`
- `reviewer_id`
- `status_after_review`
- `notes`
- `reviewed_at`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`
- `reviewer_id -> users.id`

Index:

- index `rps_document_id`
- index `reviewer_id`
- index `status_after_review`
- index `reviewed_at`

### `ai_generation_logs`
Kolom:

- `id`
- `rps_document_id`
- `user_id`
- `feature_type`
- `input_payload`
- `output_payload`
- `is_applied`
- `generated_at`
- `created_at`
- `updated_at`

Foreign key:

- `rps_document_id -> rps_documents.id`
- `user_id -> users.id`

Index:

- index `rps_document_id`
- index `user_id`
- index `feature_type`
- index `is_applied`
- index `generated_at`

## 4. Aturan Foreign Key dan Delete Behavior
Kebijakan teknis:

- Relasi master ke child memakai `restrict` atau penonaktifan, bukan cascade delete.
- Relasi `rps_documents` ke tabel detail dapat memakai cascade delete hanya untuk penghapusan draft yang sah.
- Dokumen approved sebaiknya tidak dihapus lewat flow normal.
- User yang terlibat historis tidak dihapus keras.

Prinsip praktis:

- Master data memakai `is_active` untuk soft business behavior.
- Dokumen RPS belum perlu soft delete pada MVP.

## 5. Route Map Final

### Public dan Auth
- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `GET /forgot-password`
- `POST /forgot-password`
- `GET /reset-password/{token}`
- `POST /reset-password`
- `POST /logout`

### Shared Authenticated
- `GET /dashboard`
- `GET /settings/profile`
- `PATCH /settings/profile`
- `DELETE /settings/profile`
- `GET /settings/security`
- `PUT /settings/password`

### Admin Master Data
- `GET /study-programs`
- `GET /study-programs/create`
- `POST /study-programs`
- `GET /study-programs/{studyProgram}/edit`
- `PUT /study-programs/{studyProgram}`
- `GET /curricula`
- `GET /curricula/create`
- `POST /curricula`
- `GET /curricula/{curriculum}/edit`
- `PUT /curricula/{curriculum}`
- `GET /courses`
- `GET /courses/create`
- `POST /courses`
- `GET /courses/{course}/edit`
- `PUT /courses/{course}`
- `GET /program-learning-outcomes`
- `GET /program-learning-outcomes/create`
- `POST /program-learning-outcomes`
- `GET /program-learning-outcomes/{programLearningOutcome}/edit`
- `PUT /program-learning-outcomes/{programLearningOutcome}`
- `GET /rps-templates`
- `GET /rps-templates/create`
- `POST /rps-templates`
- `GET /rps-templates/{rpsTemplate}/edit`
- `PUT /rps-templates/{rpsTemplate}`
- `GET /users`
- `GET /users/create`
- `POST /users`
- `GET /users/{user}/edit`
- `PUT /users/{user}`

Catatan:

- Pada MVP, lebih baik sediakan aksi nonaktifkan daripada delete publik.

### RPS Dokumen
- `GET /rps`
- `GET /rps/create`
- `POST /rps`
- `GET /rps/{rpsDocument}`
- `GET /rps/{rpsDocument}/edit`
- `PUT /rps/{rpsDocument}/basic-information`
- `PUT /rps/{rpsDocument}/selected-plos`
- `POST /rps/{rpsDocument}/course-outcomes`
- `PUT /rps/{rpsDocument}/course-outcomes/{courseOutcome}`
- `DELETE /rps/{rpsDocument}/course-outcomes/{courseOutcome}`
- `PUT /rps/{rpsDocument}/mappings`
- `PUT /rps/{rpsDocument}/weeks`
- `PUT /rps/{rpsDocument}/references`
- `GET /rps/{rpsDocument}/preview`

### RPS State Transition
- `POST /rps/{rpsDocument}/submit-review`
- `POST /rps/{rpsDocument}/create-revision`

### Review
- `GET /reviews`
- `GET /reviews/{rpsDocument}`
- `POST /reviews/{rpsDocument}/return-to-draft`
- `POST /reviews/{rpsDocument}/approve`

### AI Assistant
- `POST /rps/{rpsDocument}/ai/course-outcomes`
- `POST /rps/{rpsDocument}/ai/mappings`
- `POST /rps/{rpsDocument}/ai/weeks`
- `POST /rps/{rpsDocument}/ai/alignment-review`
- `POST /rps/{rpsDocument}/ai/rewrite-narrative`

## 6. Controller Map
Controller yang sesuai:

- `DashboardController`
- `Settings/ProfileController`
- `Settings/SecurityController`
- `StudyProgramController`
- `CurriculumController`
- `CourseController`
- `ProgramLearningOutcomeController`
- `RpsTemplateController`
- `UserController`
- `RpsDocumentController`
- `RpsCourseOutcomeController`
- `RpsCplMappingController`
- `RpsWeekController`
- `RpsReferenceController`
- `RpsWorkflowController`
- `RpsReviewController`
- `RpsAiAssistantController`

Catatan:

- State transition dipusatkan di `RpsWorkflowController`.

## 7. Page Map Final
Struktur page Inertia:

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

## 8. Komponen Kunci
Komponen penting:

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

## 9. Alur Save Per Step
Alur save final:

- Step 1: `PUT /rps/{id}/basic-information`
- Step 2: `PUT /rps/{id}/selected-plos`
- Step 3: `POST/PUT/DELETE /rps/{id}/course-outcomes...`
- Step 4: `PUT /rps/{id}/mappings`
- Step 5: `PUT /rps/{id}/weeks`
- Step 6: `PUT /rps/{id}/references`
- Step 7: AI review, tidak wajib simpan permanen kecuali user menerapkan hasil
- Step 8: `POST /rps/{id}/submit-review`

## 10. Validasi Route-Level Penting
Route yang wajib memiliki validasi domain kuat:

- `POST /rps`
  Pastikan konteks dasar valid.
- `PUT /rps/{id}/selected-plos`
  Pastikan CPL berasal dari prodi yang sama.
- `PUT /rps/{id}/mappings`
  Pastikan semua relasi sah dalam dokumen yang sama.
- `PUT /rps/{id}/weeks`
  Pastikan nomor minggu unik.
- `POST /rps/{id}/submit-review`
  Jalankan validasi global dokumen.
- `POST /reviews/{id}/approve`
  Pastikan reviewer bukan creator dan status dokumen `in_review`.
- `POST /rps/{id}/create-revision`
  Hanya untuk dokumen `approved`.

## 11. Prioritas Implementasi Database
Urutan tabel terbaik:

1. `users`
2. `study_programs`
3. `curricula`
4. `courses`
5. `program_learning_outcomes`
6. `rps_templates`
7. `rps_documents`
8. `rps_document_selected_plos`
9. `rps_course_outcomes`
10. `rps_cpl_mappings`
11. `rps_weeks`
12. `rps_references`
13. `rps_reviews`
14. `ai_generation_logs`

## 12. Keputusan Final
Keputusan final yang perlu dijaga tetap konsisten saat coding:

- `rps_document_selected_plos` wajib ada.
- `parent_document_id` di `rps_documents` dipakai untuk revisi.
- Delete master data secara keras dihindari.
- State transition memakai endpoint aksi khusus.
- Builder memakai save per step.
- Dokumen approved read-only penuh.
- Duplicate final RPS dikontrol lewat business rule, bukan unique DB yang terlalu kaku.
