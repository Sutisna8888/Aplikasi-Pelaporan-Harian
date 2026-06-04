# Sistem Pelaporan Harian BPS

Sistem informasi berbasis web yang dibangun menggunakan framework Laravel 13 untuk memfasilitasi pelaporan harian kinerja pegawai di lingkungan Badan Pusat Statistik (BPS) Kota Sukabumi.

## Persyaratan Sistem (System Requirements)
Sebelum menginstal aplikasi ini di server, pastikan server BPS sudah terinstal:
- PHP >= 8.3 
- Composer
- Node.js & NPM
- MySQL atau MariaDB
- Web Server (Apache/Nginx)

## Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini di server lokal atau production:

1. **Clone Repository**
   Download atau clone repository ini ke dalam direktori server Anda (misal: `htdocs` atau `/var/www/html`).
   ```bash
   git clone [https://github.com/Sutisna8888/Aplikasi-Pelaporan-Harian.git]
   cd sistem-pelaporan-harian-bps
   ```

2. **Instalasi Dependensi PHP (Vendor)**
   Jalankan perintah Composer untuk menginstal semua library PHP yang dibutuhkan oleh Laravel.
   ```bash
   composer install
   ```

3. **Instalasi Dependensi Frontend (Node Modules)**
   Jalankan perintah NPM untuk menginstal dan mem-build aset frontend (Tailwind CSS/Vite).
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment (.env)**
   Copy file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` yang baru dibuat, lalu sesuaikan konfigurasi database server BPS:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_bps
   DB_USERNAME=username_database
   DB_PASSWORD=password_database
   ```

5. **Generate Application Key**
   Jalankan perintah ini untuk men-generate kunci enkripsi aplikasi Laravel.
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeder**
   Jalankan perintah ini untuk membuat struktur tabel di database dan memasukkan data awal (akun default dan data master kegiatan).
   ```bash
   php artisan migrate --seed
   ```

7. **Link Storage**
   Jalankan perintah ini agar file gambar/dokumen yang di-upload oleh user dapat diakses oleh publik.
   ```bash
   php artisan storage:link
   ```

8. **Hak Akses Direktori (Khusus Server Linux/Mac)**
   Pastikan folder `storage` dan `bootstrap/cache` memiliki hak akses (permission) yang tepat agar Laravel dapat menulis file ke dalamnya.
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 🔐 Informasi Akun Default

Setelah instalasi berhasil, Anda dapat login menggunakan akun default berikut yang di-generate oleh sistem (Seeder):

**1. Akun Admin**
- **Username / NIP:** `admin_bps` atau `199001012024011001`
- **Email:** admin@bps.go.id
- **Password:** `password123`

**2. Akun Pegawai**
- **Username / NIP:** `pegawai_bps` atau `199505052024012002`
- **Email:** pegawai@bps.go.id
- **Password:** `password123`

> [!WARNING]
> **PERHATIAN KEAMANAN:** 
> Demi keamanan sistem, setelah berhasil login untuk pertama kalinya menggunakan akun di atas, **TIM BPS WAJIB SEGERA MENGGANTI PASSWORD DEFAULT TERSEBUT** melalui menu pengaturan akun di dalam aplikasi. Jangan biarkan password tetap `password123` saat aplikasi sudah masuk tahap production!
