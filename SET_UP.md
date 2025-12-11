# ⚙️ Panduan Instalasi (Setup Guide)

Dokumen ini menjelaskan langkah-langkah untuk menyiapkan dan menjalankan proyek **Polsri Self-Service Library** di lingkungan lokal Anda.

---

## 📋 Prasyarat

Sebelum memulai, pastikan komputer Anda telah terinstal software berikut:

1.  **PHP** (Versi 8.2 atau lebih baru)
2.  **Composer** (Dependency Manager untuk PHP)
3.  **Node.js & NPM** (Untuk mengelola aset frontend)
4.  **Database** (MySQL atau SQLite)
5.  **Git** (Untuk version control)

---

## 🚀 Langkah-Langkah Instalasi

### 1. Clone Repository

Unduh kode sumber proyek ke komputer lokal Anda:

```bash
git clone https://github.com/wahyu2021/polsri-self-service-library.git
cd polsri-self-service-library
```

### 2. Install Dependencies (Backend)

Install library PHP yang dibutuhkan menggunakan Composer:

```bash
composer install
```

### 3. Install Dependencies (Frontend)

Install library JavaScript yang dibutuhkan menggunakan NPM:

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file konfigurasi contoh `.env.example` menjadi `.env`:

```bash
cp .env.example .env
# Atau di Windows (Command Prompt):
copy .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi berikut:

*   **Database:** Sesuaikan `DB_CONNECTION`, `DB_HOST`, dll sesuai database Anda. Jika menggunakan SQLite, cukup set `DB_CONNECTION=sqlite` dan hapus konfigurasi DB lainnya.
*   **Security Key:** Generate key aplikasi baru.

```bash
php artisan key:generate
```

*   **QR Code Secret:** Pastikan variable ini ada (opsional, bisa diganti untuk keamanan lebih):

```env
LOGBOOK_QR_SECRET="secure-polsri-2025"
```

### 5. Setup Database

Jalankan migrasi untuk membuat tabel database dan seeder untuk data awal (Admin default, dll):

```bash
# Untuk Database baru (Refresh akan menghapus data lama jika ada)
php artisan migrate:fresh --seed
```

### 6. Build Aset Frontend

Kompilasi file CSS dan JS (Tailwind):

```bash
npm run build
```

---

## ▶️ Menjalankan Aplikasi

Anda memerlukan dua terminal yang berjalan bersamaan (atau gunakan fitur `dev` Laravel jika tersedia).

**Terminal 1 (Server Laravel):**
```bash
php artisan serve
```
Server akan berjalan di `http://127.0.0.1:8000`.

**Terminal 2 (Vite Development Server - Opsional untuk Hot Reload):**
```bash
npm run dev
```

---

## 🔑 Akun Default (Seeder)

Jika Anda menjalankan `--seed`, sistem akan membuat akun default berikut:

**Administrator:**
*   **Email:** `admin@polsri.ac.id`
*   **Password:** `password`

**Mahasiswa (Contoh):**
*   **NIM:** `062030701234`
*   **Password:** `password`

---

## ⚠️ Catatan Penting

*   **Fitur Logbook & GPS:** Fitur scan QR code memerlukan koneksi HTTPS (secure context) pada browser mobile modern untuk mengakses kamera dan GPS. Untuk testing di local network (buka di HP), Anda mungkin perlu menggunakan *Ngrok* atau *Laravel Valet Sharing* untuk mendapatkan URL HTTPS sementara.
*   **QR Code:** Untuk mencoba fitur scan logbook, login sebagai Admin, pergi ke **Pengaturan**, dan cetak QR Code Pintu Masuk.

---

*Dibuat oleh Foxe*
