# RDP Produk V1

## Nama Sementara
Sistem Penyusunan RPS Berbasis OBE

## 1. Latar Belakang
Penyusunan RPS di lingkungan perguruan tinggi sering memakan waktu, repetitif, dan rentan tidak konsisten dengan CPL, CPMK, serta format institusi. Di sisi lain, kampus sudah memiliki template RPS dan CPL program studi yang dapat dijadikan sumber resmi.

Aplikasi ini dirancang untuk membantu dosen dan pengelola akademik menyusun RPS dengan lebih cepat, lebih konsisten, dan tetap terkontrol secara akademik melalui pendekatan:

- `template-driven`
- `data-first`
- `AI-assisted`

## 2. Tujuan Produk
Tujuan utama aplikasi:

- Mempermudah penyusunan draft RPS berbasis OBE.
- Menjaga konsistensi antara CPL, CPMK, dan rencana pembelajaran mingguan.
- Memastikan format RPS mengikuti template institusi.
- Memberikan bantuan AI pada tahap yang paling memakan waktu, tanpa menggantikan otoritas akademik pengguna.
- Menyediakan alur review dan approval sederhana.
- Menyimpan arsip RPS per periode secara stabil.

## 3. Ruang Lingkup Produk
Ruang lingkup v1:

- Aplikasi ini khusus untuk penyusunan RPS.
- Tidak ada rencana integrasi ke SIAKAD atau aplikasi eksternal lain pada fase ini.
- Seluruh data master dikelola secara internal oleh aplikasi.
- Penyusunan RPS tetap dapat dilakukan secara manual penuh tanpa AI.
- AI digunakan sebagai asisten penyusunan, bukan sumber keputusan akhir.

## 4. Prinsip Produk
Prinsip inti yang disepakati:

- AI adalah `assistant`, bukan `decision maker`.
- Data master adalah sumber resmi.
- Dokumen RPS adalah `snapshot`.
- Hasil AI selalu dapat diedit.
- Dokumen `approved` bersifat `read-only`.
- Revisi dilakukan dengan membuat draft baru dari dokumen final.
- Sistem harus tetap usable walau AI tidak dipakai.

## 5. Aktor Sistem
Aktor utama:

- `Admin`
  Mengelola master data, template, dan pengguna.
- `Dosen`
  Menyusun, mengedit, dan mengajukan RPS.
- `Reviewer / Kaprodi`
  Mereview dan menyetujui atau mengembalikan RPS.

## 6. Sasaran MVP
MVP dianggap berhasil bila:

- Admin dapat menyiapkan master data inti.
- Dosen dapat membuat draft RPS lengkap.
- AI dapat membantu menyusun CPMK dan rencana mingguan.
- Reviewer dapat memberi catatan dan approval sederhana.
- RPS dapat dipreview dan diexport.

## 7. Modul Utama MVP
Modul yang masuk MVP:

- Autentikasi dan otorisasi dasar.
- Manajemen program studi.
- Manajemen kurikulum.
- Manajemen mata kuliah.
- Manajemen CPL.
- Manajemen template RPS.
- Builder RPS.
- AI assistant.
- Review dan approval.
- Preview dan export.

Modul yang ditunda:

- Integrasi eksternal.
- Chat AI bebas.
- Workflow approval bertingkat.
- Analitik mutu kompleks.
- Kolaborasi real-time.
- Komentar inline.
- Diff revisi kompleks.

## 8. Entitas Bisnis Inti
Entitas utama MVP:

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

## 9. Relasi Inti
Relasi bisnis utama:

- Satu `StudyProgram` memiliki banyak `Curriculum`.
- Satu `StudyProgram` memiliki banyak `ProgramLearningOutcome`.
- Satu `Curriculum` memiliki banyak `Course`.
- Satu `Course` memiliki banyak `RpsDocument`.
- Satu `RpsTemplate` dapat digunakan banyak `RpsDocument`.
- Satu `User` dapat membuat banyak `RpsDocument`.
- Satu `RpsDocument` memiliki banyak `RpsDocumentSelectedPlo`.
- Satu `RpsDocument` memiliki banyak `RpsCourseOutcome`.
- Satu `RpsDocument` memiliki banyak `RpsWeek`.
- Satu `RpsDocument` memiliki banyak `RpsReference`.
- Satu `RpsDocument` memiliki banyak `RpsReview`.
- Satu `RpsDocument` memiliki banyak `AiGenerationLog`.
- `RpsCourseOutcome` berelasi many-to-many dengan `ProgramLearningOutcome` melalui `RpsCplMapping`.

## 10. Definisi Konseptual Data

- `CPL` adalah master resmi program studi.
- `CPMK` berada dalam konteks dokumen RPS, bukan master global.
- `RpsDocument` adalah snapshot dokumen kerja dan dokumen final.
- `RpsWeek` menyimpan isi rencana pembelajaran mingguan.
- `AiGenerationLog` menyimpan jejak saran AI.

## 11. Field Penting per Entitas
Ringkasan field penting:

### `users`
- `name`
- `email`
- `password`
- `role`
- `is_active`

### `study_programs`
- `name`
- `code`
- `degree_level`
- `department_name`
- `faculty_name`
- `is_active`

### `curricula`
- `study_program_id`
- `name`
- `code`
- `academic_year`
- `description`
- `is_active`

### `courses`
- `curriculum_id`
- `study_program_id`
- `code`
- `name`
- `credit_units`
- `semester`
- `course_type`
- `description`
- `is_active`

### `program_learning_outcomes`
- `study_program_id`
- `code`
- `statement`
- `category`
- `sequence`
- `is_active`

### `rps_templates`
- `name`
- `code`
- `version`
- `description`
- `default_weeks_count`
- `layout_key`
- `is_active`

### `rps_documents`
- `course_id`
- `study_program_id`
- `curriculum_id`
- `rps_template_id`
- `created_by`
- `reviewed_by`
- `title`
- `semester_label`
- `academic_year`
- `lecturer_name`
- `coordinator_name`
- `course_description`
- `learning_materials_overview`
- `assessment_overview`
- `status`
- `review_notes_summary`
- `approved_at`

### `rps_document_selected_plos`
- `rps_document_id`
- `program_learning_outcome_id`
- `plo_code_snapshot`
- `plo_statement_snapshot`
- `sequence`

### `rps_course_outcomes`
- `rps_document_id`
- `code`
- `statement`
- `sequence`
- `indicator`
- `taxonomy_level`

### `rps_cpl_mappings`
- `rps_document_id`
- `rps_course_outcome_id`
- `program_learning_outcome_id`
- `notes`
- `alignment_strength`

### `rps_weeks`
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

### `rps_references`
- `rps_document_id`
- `reference_type`
- `citation_text`
- `is_primary`
- `sequence`

### `rps_reviews`
- `rps_document_id`
- `reviewer_id`
- `status_after_review`
- `notes`
- `reviewed_at`

### `ai_generation_logs`
- `rps_document_id`
- `user_id`
- `feature_type`
- `input_payload`
- `output_payload`
- `is_applied`
- `generated_at`

## 12. Sumber Pengisian Data
Kategori pengisian:

### Dari master data
- Program studi.
- Kurikulum.
- Mata kuliah.
- CPL.
- Template.
- Sebagian deskripsi dasar mata kuliah.

### Diisi manual pengguna
- Dosen pengampu.
- Deskripsi akhir mata kuliah.
- CPMK final.
- Mapping final.
- Isi mingguan final.
- Referensi.
- Catatan review.

### Dibantu AI
- Draft deskripsi.
- Saran CPL relevan.
- Draft CPMK.
- Saran mapping CPL ke CPMK.
- Draft rencana mingguan.
- Review alignment.
- Perbaikan redaksi.

### Diisi otomatis sistem
- Status awal.
- Creator.
- Reviewer.
- `approved_at`.
- `generated_at`.
- Urutan default.
- Metadata audit.

## 13. Workflow Builder RPS
Builder disusun sebagai wizard 8 langkah:

1. Informasi Dasar
   - Pilih prodi, kurikulum, mata kuliah, template, dan periode.
   - Isi dosen pengampu dan deskripsi.
2. CPL Terkait
   - Pilih CPL yang relevan.
3. CPMK
   - Susun manual atau generate draft CPMK dengan AI.
4. Mapping CPL ke CPMK
   - Petakan manual atau gunakan saran AI.
5. Rencana Pembelajaran Mingguan
   - Susun minggu 1 sampai N.
   - AI dapat generate draft mingguan.
6. Referensi dan Ringkasan Asesmen
   - Isi referensi utama dan pendukung.
   - Isi ringkasan asesmen.
7. Review Alignment
   - Sistem dan AI memeriksa konsistensi.
8. Preview dan Submit
   - Preview hasil.
   - Simpan draft atau submit review.

## 14. Fitur AI pada MVP
Fitur AI yang masuk MVP:

- Saran rumusan CPMK.
- Saran mapping CPL ke CPMK.
- Generate rencana pembelajaran mingguan.
- Review alignment RPS.
- Perapihan redaksi akademik.

Fitur AI yang ditunda:

- Chat bebas.
- Multi-agent workflow.
- RAG kompleks.
- One-click final RPS penuh.

## 15. Halaman Utama MVP
Area Admin:

- Dashboard Admin.
- Program Studi.
- Kurikulum.
- Mata Kuliah.
- CPL.
- Template RPS.
- Pengguna.

Area Dosen:

- Dashboard Dosen.
- RPS Saya.
- Buat RPS Baru.
- Builder RPS.
- Detail RPS.
- Edit RPS.
- Preview RPS.

Area Reviewer:

- Dashboard Reviewer.
- Daftar Review RPS.
- Halaman Review RPS.

Halaman umum:

- Login.
- Register jika diperlukan.
- Profil.
- Keamanan.

## 16. Aturan Keunikan
Aturan unik yang disarankan:

- `study_programs.code` unik.
- `rps_templates.code` unik.
- `program_learning_outcomes.code` unik dalam lingkup program studi.
- `courses.code` unik minimal dalam lingkup prodi atau kurikulum.
- Satu `rps_document` unik secara bisnis untuk kombinasi `course + academic_year + semester_label + lecturer_name` bila asumsi satu dosen satu RPS per periode dipakai.

## 17. Status Dokumen
Status inti:

- `draft`
- `in_review`
- `approved`

Makna:

- `draft`: editable bebas oleh pembuat.
- `in_review`: menunggu keputusan reviewer.
- `approved`: final dan read-only.

## 18. State Transition
Transisi yang diizinkan:

- `draft -> in_review`
- `in_review -> draft`
- `in_review -> approved`

Transisi yang tidak diizinkan:

- `draft -> approved`
- Edit langsung dokumen `approved`

Revisi dokumen approved:

- Dilakukan dengan clone ke draft baru.

## 19. Validasi Domain
Validasi minimum untuk submit review:

- Informasi dasar lengkap.
- Minimal 1 CPL dipilih.
- Minimal 1 CPMK ada.
- Semua CPMK memiliki mapping ke CPL.
- Rencana mingguan minimum terisi.
- Referensi minimum ada.
- Tidak ada error struktural.

Contoh error:

- Belum ada CPL.
- Belum ada CPMK.
- CPMK tanpa mapping.
- Dokumen tanpa minggu.

Contoh warning:

- Beban mingguan tidak merata.
- Referensi terlalu sedikit.
- Redaksi terlalu umum.

## 20. Aturan Review

- Reviewer mereview dokumen berstatus `in_review`.
- Reviewer bisa memberi catatan.
- Reviewer bisa mengembalikan ke `draft`.
- Reviewer bisa mengubah ke `approved`.
- Pembuat dokumen tidak sebaiknya approve dokumennya sendiri.
- Pengembalian ke draft wajib memiliki catatan.

## 21. Aturan Snapshot
Prinsip snapshot:

- Saat draft dibuat, data penting dari master disalin ke dokumen.
- Perubahan master tidak otomatis mengubah draft atau approved.
- Dokumen approved tetap stabil sebagai arsip.

Yang menjadi snapshot:

- Identitas prodi.
- Kurikulum.
- Mata kuliah.
- CPL terpilih.
- Template.
- Dosen pengampu.
- Isi RPS.

## 22. Aturan Revisi

- Dokumen approved tidak diedit langsung.
- Revisi dilakukan melalui aksi `buat revisi`.
- Sistem membuat salinan penuh ke draft baru.
- Dokumen lama tetap utuh.
- Hubungan antar versi perlu tercatat.

## 23. Kebijakan Edge Case
Kebijakan penting:

### Jika role pengguna berubah
- Histori pembuat dokumen tetap.

### Jika reviewer diganti
- Riwayat reviewer lama tetap tersimpan.

### Jika template berubah
- Draft dan approved lama tetap memakai snapshot lama.
- Sinkronisasi hanya manual.

### Jika CPL master berubah
- Tidak menimpa dokumen existing.
- Dokumen baru dapat memakai CPL terbaru.

### Jika mata kuliah master berubah
- Tidak menimpa dokumen existing.

### Jika master data sudah dipakai
- Lebih baik dinonaktifkan daripada dihapus.

### Jika AI menghasilkan saran buruk
- Pengguna boleh abaikan.
- Tidak pernah overwrite final otomatis.

### Jika draft terbengkalai
- Tetap tersimpan sebagai draft.
- Tidak auto-expire pada MVP.

## 24. Aturan Kepemilikan dan Akses

- Admin dapat mengelola semua master data dan melihat semua RPS.
- Dosen dapat membuat dan mengedit RPS miliknya selama status `draft`.
- Dosen dapat melihat RPS miliknya di semua status.
- Reviewer dapat melihat dan memproses RPS `in_review`.
- Hanya reviewer atau admin yang dapat approve.

## 25. Alur Bisnis Utama
Skenario inti:

- Dosen membuat draft baru.
- Dosen menyimpan draft berkala.
- Dosen submit review.
- Reviewer mengembalikan draft.
- Reviewer approve.
- Dosen membuat revisi dari dokumen approved.

## 26. Prioritas Implementasi MVP
Urutan paling aman:

1. Auth dan role.
2. Master prodi.
3. Master kurikulum.
4. Master mata kuliah.
5. Master CPL.
6. Template RPS.
7. Daftar RPS dosen.
8. Builder RPS manual.
9. Mapping CPL-CPMK.
10. Rencana mingguan.
11. Preview.
12. Review.
13. AI saran CPMK.
14. AI generate mingguan.
15. AI review alignment.
16. Export PDF.

## 27. Batasan MVP
Belum masuk:

- Integrasi eksternal.
- Multi-template dinamis kompleks.
- Approval multi-level.
- Chat AI penuh.
- Comment thread.
- Diff revisi kompleks.
- Analitik mutu.
- Import dokumen cerdas.
- Kolaborasi real-time.

## 28. Definisi Sukses V1
Produk v1 sukses jika:

- Admin bisa menyiapkan data master inti.
- Dosen bisa menyusun draft RPS end-to-end.
- AI mempercepat pekerjaan nyata, terutama CPMK dan mingguan.
- Reviewer bisa memutuskan layak atau tidak.
- Dokumen final bisa dipreview dan diexport.
- Arsip per periode tetap stabil.

## 29. Rekomendasi Arsitektur Produk
Arah yang paling sehat untuk aplikasi ini:

- `single-purpose RPS platform`
- `template-driven`
- `snapshot-based document model`
- `manual-first, AI-assisted`
- `reviewable and auditable`

## 30. Penutup
Rancangan ini sudah cukup matang untuk dijadikan fondasi implementasi. Secara produk, keputusan-keputusan pentingnya sudah terkunci: aktor, modul, data inti, workflow builder, peran AI, lifecycle dokumen, snapshot policy, dan edge-case policy.
