# Nextronix - Laravel 11 Technology Catalog + Admin Panel

Nextronix adalah **website katalog produk teknologi berbasis Laravel 11**. Versi ini dibuat lebih simpel untuk demo: customer umum tidak perlu login, tidak ada cart/checkout, dan seluruh pengaturan produk dilakukan melalui admin panel.

## Konsep Aplikasi

### Halaman Umum
- Home
- Katalog produk
- Detail produk
- Contact

### Halaman Admin
- Login admin/staff
- Dashboard
- Kelola kategori
- Kelola produk
- Kelola order legacy
- Kelola user dan role

## Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | `admin@nextronix.test` | `password` |
| Staff | `staff@nextronix.test` | `password` |

Login customer dan register sudah dinonaktifkan. Login hanya menerima akun admin/staff.

## Cara Menjalankan

Masuk ke folder project, lalu jalankan:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Untuk Windows PowerShell:

```powershell
composer install
Copy-Item .env.example .env -Force
php artisan key:generate
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Buka website:

```text
http://127.0.0.1:8000
```

Buka admin:

```text
http://127.0.0.1:8000/login
```

## Teknologi

- Laravel 11
- PHP 8.2+
- Blade Template
- Bootstrap
- SQLite default untuk demo lokal
- Spatie Permission
- DomPDF, Midtrans, dan service legacy tetap tersedia untuk kompatibilitas admin/order

## Catatan Redesign

- Nama brand diubah menjadi Nextronix.
- Tema warna diubah menjadi teknologi: navy, blue, cyan, dan violet.
- Cart dan checkout public dinonaktifkan.
- Register customer diarahkan ke login admin.
- Tombol produk diarahkan ke detail/contact, bukan cart.
- Admin panel tetap aktif untuk mengatur barang.
