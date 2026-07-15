# RPS OBE Generator

Aplikasi berbasis web untuk meng-generate **Rencana Pembelajaran Semester (RPS)** berbasis **Output-Based Education (OBE)** secara otomatis menggunakan **Gemini AI** (melalui Laravel AI SDK), dibangun menggunakan Laravel 13, React (Inertia.js), TailwindCSS, Spatie Permission, dan Fortify.

---

## ✨ Fitur Utama

- **AI-Powered RPS Generation**: Men-generate CPMK (Capaian Pembelajaran Mata Kuliah) dan detail rencana kegiatan mingguan (14 minggu perkuliahan di luar UTS & UAS) menggunakan model bahasa Gemini AI.
- **Dynamic CPL Selector**: Memasukkan target Capaian Pembelajaran Lulusan (CPL) secara manual atau memilih cepat dari CPL Prodi yang telah ditentukan.
- **Tabular Editor**: Antarmuka berbasis tab yang interaktif untuk menyesuaikan/mengedit hasil draf RPS (Informasi Umum, CPL, CPMK, Rencana Kegiatan Mingguan).
- **Flexible Export**: Unduh dokumen RPS resmi dalam format:
  - **PDF** siap cetak (di-render via DomPDF).
  - **Microsoft Word (DOCX)** siap pakai, otomatis disesuaikan dengan template resmi menggunakan PhpWord.
- **Role & Permission Management**: Hak akses terproteksi untuk **Lecturer / Dosen** (membuat, mengedit, mengunduh RPS) dan **Admin** (memantau seluruh RPS dan data kurikulum).

---

## 🛠️ Tech Stack

- **Backend**: Laravel 13 & PHP 8.3+
- **Frontend**: React 19, Inertia.js v3, & TypeScript
- **Styling**: TailwindCSS v4 & Shadcn UI
- **AI Integrations**: Laravel AI SDK (`laravel/ai`) dengan Gemini API
- **Authentication**: Laravel Fortify & Spatie Permission
- **Document Export**: DomPDF & PHPOffice PhpWord

---

## 🚀 Panduan Instalasi Lokal

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- Akun Gemini API key (untuk AI generator)

### Langkah-Langkah

1. **Klon Repositori**:
   ```bash
   git clone https://github.com/mabere/rps-generator.git
   cd rps-generator
   ```

2. **Instalasi Dependensi PHP & JS**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database dan masukkan API Key Gemini Anda di file `.env`:
   ```env
   GEMINI_API_KEY=your_gemini_api_key_here
   ```

4. **Generate Aplikasi Key & Jalankan Migrasi**:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

5. **Jalankan Seeder (Roles & Permissions)**:
   ```bash
   php artisan db:seed --class=RolesAndPermissionsSeeder
   ```

6. **Build Aset Frontend**:
   ```bash
   npm run build
   ```
   Atau jalankan server development:
   ```bash
   npm run dev
   ```

---

## 👥 Akun Pengujian Bawaan

Jika Anda menjalankan seeder bawaan, Anda dapat masuk menggunakan akun percobaan berikut:

- **Lecturer / Dosen**: `lecturer@example.com` (password: `password`)
- **Admin**: `admin@example.com` (password: `password`)

---

## 📘 Panduan Penggunaan
Panduan penggunaan lengkap untuk masing-masing peran (Dosen & Admin) dapat dibaca pada file [PANDUAN_PENGGUNAAN_RPS.md](public/PANDUAN_PENGGUNAAN_RPS.md).
