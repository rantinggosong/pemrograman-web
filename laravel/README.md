# Kape QR Menu - Laravel 11 CRUD & Eloquent ORM

Aplikasi web pemesanan makanan dan minuman cafe berbasis QR Code yang dibangun menggunakan framework Laravel 11, PHP 8.3, dan MySQL.

## Spesifikasi Teknis

- **Framework:** Laravel 11
- **PHP Version:** 8.3+
- **Database:** MySQL (Database: `db_cafe_menu`)
- **Frontend Stack:** Tailwind CSS (Utility-First), Google Fonts (Playfair Display & Plus Jakarta Sans)
- **Arsitektur:** Model-View-Controller (MVC) & Eloquent Object-Relational Mapping (ORM)

## Skema Basis Data

- **Tabel `categories`:** Menyimpan kategori menu (`id`, `nama_kategori`, `slug`, `timestamps`).
- **Tabel `menus`:** Menyimpan daftar kuliner dengan foreign key `category_id` terikat ke tabel `categories` (`cascadeOnDelete`).

## Panduan Instalasi & Menjalankan

1. Salin berkas lingkungan:
   ```bash
   cp .env.example .env
   ```

2. Jalankan migrasi dan seeder data:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

4. Buka peramban pada `http://127.0.0.1:8000`.
