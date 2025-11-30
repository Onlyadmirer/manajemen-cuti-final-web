# Sistem Manajemen Cuti

Aplikasi manajemen cuti berbasis web yang dibangun dengan Laravel 12 untuk mengelola pengajuan cuti karyawan, persetujuan, dan administrasi kepegawaian.

## Fitur

-   Manajemen pengguna dengan role (Admin, HR, Manager, Employee)
-   Pengajuan dan persetujuan cuti
-   Manajemen divisi
-   Manajemen hari libur
-   Dashboard untuk setiap role
-   Laporan cuti
-   Export PDF untuk dokumen cuti
-   Notifikasi (terintegrasi dengan Fonnte)

## Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   SQLite (default) atau MySQL/PostgreSQL
-   Git

## Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Onlyadmirer/manajemen-cuti-final-web.git
cd manajemen-cuti-final-web
```

### 2. Install Dependencies

Install PHP dependencies menggunakan Composer:

```bash
composer install
```

Install JavaScript dependencies menggunakan NPM:

```bash
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Atau di Windows (PowerShell):

```bash
copy .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Secara default, aplikasi menggunakan SQLite. File database akan dibuat otomatis saat migrasi.

**Untuk SQLite (Recommended):**

-   Tidak perlu konfigurasi tambahan, sudah terkonfigurasi di `.env`

**Untuk MySQL/PostgreSQL:**

-   Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemen_cuti
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi dan Seeder

Jalankan migrasi database dan seeder untuk membuat tabel dan data awal:

```bash
php artisan migrate:fresh --seed
```

### 7. Build Assets

Build asset frontend (CSS/JS):

```bash
npm run build
```

### 8. Jalankan Aplikasi

**Untuk Development:**

Jalankan server development:

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

**Untuk Development dengan Hot Reload:**

Di terminal pertama:

```bash
php artisan serve
```

Di terminal kedua:

```bash
npm run dev
```

### 9. Login ke Aplikasi

Setelah seeder berjalan, Anda dapat login dengan akun default yang telah dibuat.

**Catatan:** Lihat file `database/seeders/DatabaseSeeder.php` untuk informasi kredensial login.

## Storage Link (Opsional)

Jika aplikasi menggunakan file upload, jalankan:

```bash
php artisan storage:link
```

## Troubleshooting

### Permission Issues (Linux/Mac)

Jika ada masalah permission pada folder `storage` dan `bootstrap/cache`:

```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache

Jika mengalami masalah, coba clear cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Development Commands

```bash
# Menjalankan tests
php artisan test

# Code formatting
./vendor/bin/pint

# Watch file changes (Vite)
npm run dev

# Build untuk production
npm run build
```

## Struktur Project

```
app/
├── Http/Controllers/  # Controllers
├── Models/           # Eloquent Models
├── Services/         # Business Logic
└── View/Components/  # Blade Components

database/
├── migrations/       # Database Migrations
└── seeders/         # Database Seeders

resources/
├── views/           # Blade Templates
├── css/            # Stylesheets
└── js/             # JavaScript

routes/
└── web.php         # Web Routes
```

## Tech Stack

-   **Backend:** Laravel 12
-   **Frontend:** Blade, Alpine.js, Tailwind CSS
-   **Database:** SQLite (default) / MySQL / PostgreSQL
-   **PDF Generator:** DomPDF
-   **Authentication:** Laravel Breeze

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
