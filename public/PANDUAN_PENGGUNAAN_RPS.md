# Panduan Penggunaan Fitur RPS OBE Generator

Dokumen ini berisi panduan langkah-demi-langkah penggunaan fitur generator Rencana Pembelajaran Semester (RPS) berbasis Output-Based Education (OBE) untuk peran **Dosen** dan **Admin**.

---

## 1. Peran: Dosen (Lecturer)

Dosen memiliki hak akses untuk membuat, mengedit, mempublikasikan, dan mengekspor dokumen RPS dari mata kuliah yang diampunya.

### Langkah 1: Akses Dashboard RPS
1. Log in ke aplikasi dengan akun Dosen Anda.
2. Di sidebar navigasi sebelah kiri, klik menu **RPS OBE**.
3. Anda akan melihat halaman **Daftar RPS** yang menampilkan semua dokumen RPS yang telah dibuat (baik status *Draft* maupun *Published*).

### Langkah 2: Membuat / Men-generate RPS Baru
1. Di pojok kanan atas halaman daftar RPS, klik tombol **Buat RPS Baru**.
2. Anda akan dialihkan ke halaman **Generate RPS Baru**.
3. **Pilih Mata Kuliah**: Pilih mata kuliah dari dropdown yang tersedia. Informasi detail mata kuliah (SKS, Semester, Deskripsi Singkat) akan muncul secara otomatis.
4. **Pilih / Input CPL**:
   - Anda dapat memilih CPL bawaan dari daftar CPL Program Studi dengan mengklik kode CPL (misal: `+S3`, `+P1`).
   - Jika CPL tidak ada dalam daftar, klik **Tambah CPL Manual** untuk membuat CPL kustom langsung dari halaman ini.
5. Klik tombol **Generate RPS**.
6. Gemini AI akan menganalisis data Anda dan menyusun CPMK beserta aktivitas perkuliahan mingguan (Minggu 1-14). Halaman loading akan berjalan sekitar 10-15 detik untuk menunjukkan kemajuan analisis.

### Langkah 3: Mengedit Hasil Generasi AI
Setelah proses pembuatan selesai, Anda akan otomatis masuk ke halaman **Editor RPS**:
- **Tab Informasi Umum & CPL**: Untuk melihat informasi dasar mata kuliah dan daftar CPL yang ditargetkan.
- **Tab Capaian Pembelajaran (CPMK)**: Mengedit rumusan CPMK dan indikator asesmen yang dibuat oleh AI.
- **Tab Rencana Mingguan (1-14)**: Menyesuaikan Sub-CPMK, Metode & Pengalaman Belajar, Kriteria Penilaian, Bobot Nilai (%), dan Referensi Bahan Kajian per minggu.
- Di panel sebelah kiri, ubah status dokumen menjadi **Draft** atau **Published**, lalu klik **Simpan Perubahan**.

### Langkah 4: Ekspor Dokumen
1. Dari halaman **Daftar RPS** atau halaman **Editor**, klik tombol ekspor yang diinginkan:
   - **Export PDF**: Mengunduh RPS dalam format PDF siap cetak.
   - **Export DOCX**: Mengunduh RPS dalam format file Microsoft Word yang telah disesuaikan dengan template resmi prodi.

---

## 2. Peran: Admin

Admin memiliki kontrol penuh atas manajemen kurikulum sebelum dosen dapat meng-generate RPS.

### Langkah 1: Manajemen Mata Kuliah & Program Studi
Sebelum dosen dapat membuat RPS, Admin wajib memastikan:
1. Program Studi telah terdaftar di database.
2. CPL resmi program studi telah dimasukkan ke dalam sistem agar dosen dapat memilihnya secara instan.
3. Struktur Mata Kuliah (Kode, Nama, SKS, Semester, Deskripsi Singkat) telah di-input dengan benar.

### Langkah 2: Monitoring & Supervisi RPS
1. Admin dapat memantau seluruh dokumen RPS yang sedang dikerjakan oleh dosen melalui halaman utama **RPS OBE**.
2. Admin dapat melihat dokumen yang masih berstatus **Draft** maupun yang sudah difinalisasi (**Published**).
3. Admin memiliki wewenang untuk membuka kembali akses edit jika terdapat perbaikan kurikulum tingkat program studi.

---

## 💡 Tips & Rekomendasi OBE

*   **IKU 7 (Indikator Kinerja Utama)**: Saat AI men-generate metode pembelajaran, pastikan mengutamakan metode aktif seperti *Case Method* (Pemecahan Kasus) atau *Project-Based Learning* (Pembelajaran Berbasis Proyek) untuk memenuhi kriteria IKU 7 PTN/PTS.
*   **Bobot Penilaian**: Pastikan total akumulasi bobot (%) dari pertemuan minggu 1-14 (ditambah estimasi persentase UTS di minggu ke-8 dan UAS di minggu ke-16) menghasilkan akumulasi total **100%**.
