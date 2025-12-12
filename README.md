# Polsri Self-Service Library (Smart Library)

Platform perpustakaan digital mandiri (self-service) untuk Politeknik Negeri Sriwijaya (Polsri). Sistem ini memfasilitasi manajemen logbook pengunjung berbasis QR Code & Geolocation, serta sirkulasi peminjaman buku mandiri.

## 🚀 Fitur Utama

-   **Smart Logbook (Scan QR + GPS)**: Validasi kehadiran pengunjung menggunakan QR Code dinamis dan Geofencing (lokasi GPS).
-   **Self-Service Borrowing**: Mahasiswa dapat meminjam buku secara mandiri melalui aplikasi.
-   **Admin Dashboard**: Monitoring real-time pengunjung, sirkulasi buku, dan antrean validasi.
-   **Laporan Otomatis**: Export laporan denda dan kunjungan ke format PDF (standar dinas) & CSV.
-   **Manajemen Buku & User**: CRUD data buku dan anggota perpustakaan.

## 🛠 Teknologi

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Blade, Alpine.js, Tailwind CSS (via Vite)
-   **Database**: MySQL
-   **Libraries**:
    -   `barryvdh/laravel-dompdf`: Generate laporan PDF.
    -   `simplesoftwareio/simple-qrcode`: Generate QR Code.
    -   `html5-qrcode`: Scanner QR di sisi client.
    -   `apexcharts`: Visualisasi grafik statistik.

## 📦 Instalasi & Setup

### Prasyarat
-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL

### Langkah Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/polsri-library.git
    cd polsri-library
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    
    **Konfigurasi Tambahan di .env:**
    ```env
    # URL Aplikasi (Penting untuk PDF/QR)
    APP_URL=http://localhost:8000 
    
    # Secret Key untuk QR Code Logbook (Ganti dengan string acak yang aman)
    LOGBOOK_QR_SECRET=rahasia-polsri-2025
    ```

4.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```
    *Seeder akan membuat akun Admin default dan data dummy.*

5.  **Jalankan Aplikasi**
    ```bash
    npm run dev
    php artisan serve
    ```

## 🔐 Akun Default (Demo)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@polsri.ac.id | `password` |
| **Mahasiswa** | mahasiswa@polsri.ac.id | `password` |

## 📱 Cara Penggunaan (Fitur Logbook)

1.  **Admin**:
    -   Masuk ke menu **Pengaturan**.
    -   Atur titik koordinat perpustakaan (Latitude/Longitude) dan radius validasi.
    -   Klik **"Download PDF"** pada bagian QR Code Pintu Masuk.
    -   Cetak dan tempel QR Code di pintu masuk perpustakaan.

2.  **Mahasiswa**:
    -   Login ke aplikasi.
    -   Buka menu **"Logbook"**.
    -   Izinkan akses Kamera dan Lokasi (GPS).
    -   Scan QR Code yang tertempel di pintu masuk.
    -   Sistem akan memvalidasi QR dan lokasi Anda secara otomatis.

## 📂 Struktur Folder Penting

-   `app/Services`: Logika bisnis utama (BookService, LogbookService, ScanService).
-   `app/Repositories`: Layer akses database (Clean Architecture).
-   `resources/views/admin/report/pdf.blade.php`: Template PDF laporan resmi.
-   `resources/js/student/logbook.js`: Logika scanner QR & Geolocation.

## 🤝 Kontribusi

Silakan buat *Pull Request* untuk perbaikan bug atau penambahan fitur. Pastikan kode mengikuti standar PSR-12 dan struktur Service-Repository yang telah diterapkan.

---
**FOXE**
