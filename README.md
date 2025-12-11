# 📚 Polsri Self-Service Library

> **Sistem Perpustakaan Modern & Mandiri untuk Politeknik Negeri Sriwijaya**

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38B2AC?style=for-the-badge&logo=tailwind-css)
![Status](https://img.shields.io/badge/Status-Active_Development-success?style=for-the-badge)

Sistem **Polsri Self-Service Library** adalah solusi manajemen perpustakaan modern yang dirancang untuk mendigitalkan proses sirkulasi dan presensi di UPT Perpustakaan Polsri. Aplikasi ini memprioritaskan pengalaman **Self-Service** (mandiri) bagi mahasiswa, mengurangi antrian, dan mengotomatisasi pencatatan administratif.

---

## ✨ Fitur Unggulan

### 👨‍🎓 Portal Mahasiswa (Mobile-First)
*   **Smart Logbook (Presensi QR & GPS):** Mahasiswa melakukan scan QR Code dinamis di pintu masuk. Sistem memvalidasi lokasi GPS pengguna untuk memastikan kehadiran fisik di perpustakaan.
*   **Self-Service Borrowing:** Cari buku, scan, dan ajukan peminjaman secara mandiri melalui HP.
*   **Digital Exit Pass:** Tiket digital sebagai bukti peminjaman sah saat melewati gerbang keluar.
*   **Koleksi & Notifikasi:** Riwayat peminjaman lengkap dan pengingat otomatis untuk pengembalian buku.

### 👮 Portal Admin
*   **Dashboard Analitik:** Visualisasi data kunjungan, peminjaman, dan pendapatan denda secara real-time.
*   **Manajemen Sirkulasi:** Verifikasi peminjaman, proses pengembalian, dan perhitungan denda otomatis.
*   **Geofencing & Security:** Konfigurasi radius GPS perpustakaan dan pembuat QR Code dinamis (dienkripsi) untuk keamanan logbook.
*   **Laporan Komprehensif:** Export laporan sirkulasi dan keuangan ke format CSV/Excel.
*   **Manajemen Pengguna & Buku:** CRUD lengkap untuk data anggota dan katalog buku.

---

## 🛠️ Teknologi

*   **Backend:** Laravel 11 Framework
*   **Frontend:** Blade Templates + Tailwind CSS + Alpine.js (via resource scripts)
*   **Database:** MySQL / SQLite
*   **Peta & Lokasi:** Leaflet.js (OpenStreetMap)
*   **Keamanan:** Enkripsi QR Code, Validasi Geolocation

---

## 🚀 Instalasi & Konfigurasi

Untuk panduan lengkap mengenai cara instalasi, konfigurasi database, dan menjalankan aplikasi di komputer lokal Anda, silakan baca dokumen berikut:

👉 **[PANDUAN INSTALASI (SET_UP.md)](./SET_UP.md)**

---

## 📝 Konfigurasi Khusus (.env)

Aplikasi ini menggunakan beberapa variabel lingkungan khusus untuk keamanan fitur Logbook:

```env
LOGBOOK_QR_SECRET="secure-polsri-2025"
```
*Secret key ini digunakan untuk memvalidasi QR Code yang dicetak oleh admin.*

---

## 📄 Lisensi

Proyek ini bersifat open-source dan dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---
<p align="center">
    Dibuat dengan ❤️ oleh <b>Foxe</b>
</p>