# PANDUAN LENGKAP & MATERI PRESENTASI WEEK 9: LARAVEL CRUD & ELOQUENT ORM

Dokumen ini disusun sebagai panduan resmi anggota tim pengembang **Kape QR** untuk memahami, mendemonstrasikan, dan mempresentasikan hasil pengerjaan **Week 9: Laravel CRUD + ORM** di depan dosen dan kelas. Anggota kelompok dapat langsung membaca dokumen ini tanpa perlu penjelasan ulang secara manual.

---

## DAFTAR ISI

1. [Konteks Tugas & Latar Belakang Arsitektur](#1-konteks-tugas--latar-belakang-arsitektur)
2. [Checklist 7 Instruksi Dosen (Bocoran Week 9)](#2-checklist-7-instruksi-dosen-bocoran-week-9)
3. [Struktur Folder & Standar Clean Architecture](#3-struktur-folder--standar-clean-architecture)
4. [Bedah Kode Teknis (7 Poin Implementasi)](#4-bedah-kode-teknis-7-poin-implementasi)
   - [Poin 1: Instalasi Laravel](#poin-1-instalasi-laravel)
   - [Poin 2: Konfigurasi Basis Data (.env)](#poin-2-konfigurasi-basis-data-env)
   - [Poin 3: Migrasi Multi-Tabel (Database Migrations)](#poin-3-migrasi-multi-tabel-database-migrations)
   - [Poin 4: Model & Relasi Eloquent ORM](#poin-4-model--relasi-eloquent-orm)
   - [Poin 5: Controller & Query Orchestration](#poin-5-controller--query-orchestration)
   - [Poin 6: Antarmuka Blade Responsif (Views)](#poin-6-antarmuka-blade-responsif-views)
   - [Poin 7: Routing Web (routes/web.php)](#poin-7-routing-web-routeswebphp)
5. [Skrip & Panduan Demo Presentasi di Proyektor](#5-skrip--panduan-demo-presentasi-di-proyektor)
6. [Antisipasi Pertanyaan Dosen & Kunci Jawaban](#6-antisipasi-pertanyaan-dosen--kunci-jawaban)
7. [Panduan Menjalankan Project di Laptop Anggota](#7-panduan-menjalankan-project-di-laptop-anggota)

---

## 1. KONTEKS TUGAS & LATAR BELAKANG ARSITEKTUR

### Mengapa Week 9 Berbeda dari Week 5–7?
- **Pekan 2 s/d 4:** Kita membangun klien web murni (HTML5, Tailwind CSS, DOM JavaScript, dan Fetch API).
- **Pekan 5 s/d 7:** Kita membangun arsitektur server-side dari nol (*under-the-hood*) menggunakan PHP native prosedural, OOP, hingga Custom MVC sederhana tanpa framework. Tujuannya adalah agar kita paham bagaimana HTTP request, routing, dan class bekerja secara mendasar.
- **Pekan 9 (Pasca UTS):** Kurikulum meloncat ke **standar industri dunia nyata**, yaitu menggunakan framework enterprise: **Laravel 11**. Kita tidak lagi menulis query SQL mentah (`mysqli_query` atau manual PDO), melainkan menggunakan **Eloquent ORM** (*Object-Relational Mapping*), migrasi terstruktur, serta template engine **Blade**.

---

## 2. CHECKLIST 7 INSTRUKSI DOSEN (BOCORAN WEEK 9)

Berikut adalah 7 poin instruksi yang tertulis pada modul/papan tulis perkuliahan dan status penyelesaiannya dalam proyek kita:

| No | Instruksi Perkuliahan | Status | Lokasi Berkas / Implementasi |
| :---: | :--- | :---: | :--- |
| **1** | **Instalasi Laravel** | Selesai | Direktori `laravel/` (Laravel 11 + PHP 8.3) |
| **2** | **Setting database (`.env`)** | Selesai | `laravel/.env` terhubung ke MySQL `db_cafe_menu` |
| **3** | **Buat table di database $\rightarrow$ migrations sejumlah table** | Selesai | Tabel `categories` & `menus` dengan relasi Foreign Key |
| **4** | **Buat model untuk masing-masing table** | Selesai | `App\Models\Category` & `App\Models\Menu` (relasi 1:N) |
| **5** | **Buat controller (method `index`)** | Selesai | `App\Http\Controllers\MenuController@index` |
| **6** | **Buat views (`index`)** | Selesai | `resources/views/layouts/app.blade.php` & `menu/index.blade.php` |
| **7** | **Buat route (`index`)** | Selesai | `routes/web.php` mengarahkan root `/` ke `MenuController@index` |

---

## 3. STRUKTUR FOLDER & STANDAR CLEAN ARCHITECTURE

### Mengapa Dibuat Folder `laravel/` Terpisah?
Di dalam repositori git, kita mempertahankan folder `frontend/` (Week 2-4) dan `backend/` (Week 5-7) sebagai portofolio riwayat belajar. Folder `laravel/` adalah aplikasi mandiri penuh (*monolith*).

Saat presentasi di depan kelas/dosen menggunakan proyektor:
> **Tips Presentasi:** Buka Visual Studio Code langsung pada folder `laravel/` (`File -> Open Folder -> laravel`). Dengan begitu, pohon folder di VS Code 100% rapi sesuai standar industri tanpa folder lama yang membingungkan audiens.

```
laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── MenuController.php     <- Poin 5: Eager loading, filter, & search
│   └── Models/
│       ├── Category.php               <- Poin 4: Relasi hasMany(Menu::class)
│       └── Menu.php                   <- Poin 4: Relasi belongsTo(Category::class) & Accessor
├── database/
│   ├── migrations/
│   │   ├── ..._create_categories_table.php  <- Poin 3: Skema tabel kategori
│   │   └── ..._create_menus_table.php       <- Poin 3: Skema tabel menu + foreignId
│   └── seeders/
│       ├── CategorySeeder.php         <- Seeder 3 kategori utama
│       ├── MenuSeeder.php             <- Seeder 30 data menu kuliner real
│       └── DatabaseSeeder.php         <- Seeder runner utama
├── public/
│   └── asset/                         <- Aset gambar kuliner (24 file foto)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          <- Poin 6: Master layout Blade + Tailwind CDN
│       └── menu/
│           └── index.blade.php        <- Poin 6: Katalog menu dinamis + filter
├── routes/
│   └── web.php                        <- Poin 7: Route::get('/', [MenuController::class, 'index'])
└── .env                               <- Poin 2: Konfigurasi MySQL
```

---

## 4. BEDAH KODE TEKNIS (7 POIN IMPLEMENTASI)

### Poin 1: Instalasi Laravel
Laravel 11 diinisialisasi menggunakan PHP 8.3 dan Composer dengan arsitektur modern (*lean skeleton*). Tidak ada dependensi yang usang, dan autoloading class diatur sepenuhnya melalui PSR-4.

### Poin 2: Konfigurasi Basis Data (.env)
Konfigurasi database diatur pada file `laravel/.env`:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_cafe_menu
DB_USERNAME=root
DB_PASSWORD=
```
**Poin Penting untuk Dijelaskan:**
Kita memisahkan konfigurasi sensitif ke file `.env` agar kredensial database tidak terekspos ke repositori publik (*security best practice*).

### Poin 3: Migrasi Multi-Tabel (Database Migrations)
Dosen meminta **"migrations sejumlah table"** (multi-table). Kita merancang skema relasional 1 ke N (*One-to-Many*):
1. **Tabel `categories`:**
   ```php
   Schema::create('categories', function (Blueprint $table) {
       $table->id();
       $table->string('nama_kategori');
       $table->string('slug')->unique();
       $table->timestamps();
   });
   ```
2. **Tabel `menus`:**
   ```php
   Schema::create('menus', function (Blueprint $table) {
       $table->id();
       $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
       $table->string('nama');
       $table->unsignedInteger('harga');
       $table->string('gambar')->nullable();
       $table->string('status')->default('Tersedia');
       $table->text('deskripsi')->nullable();
       $table->timestamps();
   });
   ```
**Poin Penting untuk Dijelaskan:**
`foreignId('category_id')->constrained('categories')->cascadeOnDelete()` memastikan integritas data. Jika suatu kategori dihapus, seluruh data menu di bawahnya otomatis terhapus secara aman tanpa meninggalkan data yatim (*orphan records*).

### Poin 4: Model & Relasi Eloquent ORM
Dosen meminta **"buat model untuk masing-masing table"**. Kita membuat 2 model Eloquent:

1. **`App\Models\Category.php`**
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\Relations\HasMany;

   class Category extends Model
   {
       use HasFactory;

       protected $fillable = ['nama_kategori', 'slug'];

       public function menus(): HasMany
       {
           return $this->hasMany(Menu::class);
       }
   }
   ```

2. **`App\Models\Menu.php`**
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\Relations\BelongsTo;

   class Menu extends Model
   {
       use HasFactory;

       protected $fillable = [
           'category_id', 'nama', 'harga', 'gambar', 'status', 'deskripsi',
       ];

       public function category(): BelongsTo
       {
           return $this->belongsTo(Category::class);
       }

       public function getHargaFormattedAttribute(): string
       {
           return 'Rp ' . number_format($this->harga, 0, ',', '.');
       }
   }
   ```
**Poin Penting untuk Dijelaskan:**
- Relasi: 1 Kategori memiliki banyak Menu (`hasMany`), dan setiap Menu dimiliki oleh 1 Kategori (`belongsTo`).
- Properti `$fillable` melindungi sistem dari celah keamanan *Mass Assignment Vulnerability*.
- Method `getHargaFormattedAttribute()` adalah fitur **Eloquent Accessor**. Di Blade, kita cukup memanggil `$menu->harga_formatted` dan otomatis dikonversi ke format mata uang Rupiah.

### Poin 5: Controller & Query Orchestration
Dosen meminta **"buat controller (method index)"**. Kita membangun `App\Http\Controllers\MenuController`:

```php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();

        $query = Menu::with('category');

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('category', function ($builder) use ($request) {
                $builder->where('slug', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $menus = $query->orderBy('id', 'asc')->get();

        return view('menu.index', [
            'categories' => $categories,
            'menus' => $menus,
            'activeCategory' => $request->get('kategori', 'all'),
            'searchKeyword' => $request->get('search', ''),
        ]);
    }
}
```
**Poin Penting untuk Dijelaskan:**
- `Menu::with('category')`: Menggunakan teknik **Eager Loading**. Ini mengeksekusi hanya 2 kueri SQL di balik layar (1 untuk menu, 1 untuk semua kategori terkait), sehingga menyelesaikan masalah performa **N+1 Query Problem**.
- Fitur filter kategori dan pencarian kata kunci ditangani secara dinamis melalui query builder Eloquent.

### Poin 6: Antarmuka Blade Responsif (Views)
Dosen meminta **"buat views (index)"**. Kita membangun arsitektur Blade yang mempertahankan 100% konsistensi UI/UX dari Week 2 hingga Week 7:
1. `resources/views/layouts/app.blade.php`: Master layout dengan warna latar otentik (`#D5B99F` radial gradient), palet warna kafe (`primary-light: #D5B99F`, `dark: #1A110A`, `gray-500: #5C4A3D`, `gray-800: #2C1E16`), dan Google Fonts (*Playfair Display* & *Plus Jakarta Sans*).
2. `resources/views/menu/index.blade.php`:
   - **Header & Tipografi Konsisten:** Judul font-serif `Menu` dan subjudul `Pesan langsung dari meja kamu`.
   - **Pencarian Ganda (Dual-Search):** Input pencarian responsif dengan live instant filter di browser dan fallback kueri server-side via GET request.
   - **Filter Kategori Identik:** Barisan filter horizontal (*All*, *Makanan*, *Minuman*, *Snack*) dengan garis bawah aktif `border-b-2 border-[#1A110A]`.
   - **Struktur Kartu Menu Presisi:** Kartu menu kaca semi-transparan (`bg-white/30 border border-[#1A110A]/10 rounded-2xl`), bingkai gambar gelap (`bg-[#1A110A]`), badge status produk (*Best Seller*, *Promo*, *Habis*), serta tombol aksi interaktif `Order Now (Rp ...)`.
   - **Floating Cart Summary Pill:** Komponen keranjang belanja mengambang (`#cart-ringkasan`) yang secara reaktif menghitung total item dan nominal harga saat tombol order diklik.
   - **Empty State:** Pesan visual ramah pengguna saat menu tidak ditemukan (*"Yang kamu cari tidak ditemukan"*).

### Poin 7: Routing Web (routes/web.php)
Dosen meminta **"buat route (index)"**. Rute didaftarkan secara elegan:
```php
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MenuController::class, 'index'])->name('menu.index');
```
Ketika pengguna membuka alamat root `http://127.0.0.1:8000/`, Laravel secara otomatis memanggil method `index()` pada `MenuController`.

---

## 5. SKRIP & PANDUAN DEMO PRESENTASI DI PROYEKTOR

Gunakan panduan 4 langkah berikut saat maju mempresentasikan tugas di depan kelas:

### Langkah 1: Membuka Visual Studio Code
Buka folder `laravel/` di VS Code.
> **Kalimat Presenter:**  
> *"Selamat pagi/siang Bapak/Ibu dosen dan rekan-rekan sekalian. Pada pertemuan ke-9 ini, tim kami mengimplementasikan migrasi sistem Cafe QR Menu dari arsitektur MVC manual ke framework Laravel 11. Di sini kami telah memenuhi seluruh 7 instruksi perkuliahan, mulai dari instalasi, migrasi multi-tabel, pemodelan Eloquent ORM, controller, views Blade, hingga routing."*

### Langkah 2: Menunjukkan Migrasi & Basis Data MySQL
Buka terminal VS Code dan tunjukkan tabel hasil migrasi.
> **Kalimat Presenter:**  
> *"Untuk memenuhi instruksi pembuatan tabel relasional di database, kami membuat 2 berkas migrasi: `categories` dan `menus`. Tabel `menus` memiliki foreign key `category_id` yang terikat ke tabel `categories` dengan `cascadeOnDelete`. Kami juga menyediakan seeder berisi 30 data kuliner lengkap."*

### Langkah 3: Menunjukkan Model Relasi & Controller
Buka file `app/Models/Menu.php` dan `app/Http/Controllers/MenuController.php`.
> **Kalimat Presenter:**  
> *"Pada lapisan Model, kami memanfaatkan Eloquent ORM dengan relasi `belongsTo` dan `hasMany`. Pada Controller, kami mengoptimalkan performa menggunakan Eager Loading `Menu::with('category')` guna mencegah N+1 Query Problem, serta menyertakan fungsionalitas filtering kategori dan live keyword search."*

### Langkah 4: Menjalankan & Mendemonstrasikan Antarmuka Web
Buka browser pada `http://127.0.0.1:8000/`.
> **Kalimat Presenter:**  
> *"Berikut adalah antarmuka katalog menu yang kami bangun menggunakan template engine Blade dan Tailwind CSS. Seluruh 30 menu kuliner dirender secara dinamis dari database MySQL.*  
> *(Sambil klik tombol kategori Minuman / Makanan)*  
> *Ketika kita memilih kategori atau mengetik kata kunci pencarian, Controller secara cerdas memfilter kueri Eloquent dan memperbarui tampilan secara instan."*

---

## 6. ANTISIPASI PERTANYAAN DOSEN & KUNCI JAWABAN

| Pertanyaan Dosen | Kunci Jawaban Lengkap & Benar |
| :--- | :--- |
| **Q1: "Mengapa kalian memakai `with('category')` di Controller, kenapa tidak langsung `Menu::all()`?"** | *"Jika kita memakai `Menu::all()` lalu di view memanggil `$menu->category->nama_kategori`, Laravel akan melakukan 1 query SQL baru untuk setiap kartu menu. Untuk 30 menu, itu berarti 31 query (N+1 Query Problem). Dengan `with('category')`, Laravel melakukan Eager Loading sehingga hanya mengeksekusi 2 query efisien di awal."* |
| **Q2: "Apa fungsi `$fillable` pada Model `Menu` dan `Category`?"** | *"`$fillable` adalah whitelist atribut yang diizinkan untuk diisi secara massal (Mass Assignment). Ini adalah fitur keamanan penting di Laravel untuk mencegah serangan Mass Assignment Vulnerability, di mana pengguna berbahaya mencoba menyuntikkan kolom terlarang (seperti ID atau role admin)."\* |
| **Q3: "Apa perbedaan render tampilan di Week 7 (MVC Native) dan Week 9 (Laravel)?"** | *"Di Week 7, kita merender HTML menggunakan PHP tag `<?= $menu->renderKartu(); ?>` secara manual. Di Week 9, kita menggunakan template engine Blade (`@extends`, `@section`, `@forelse`) yang otomatis melakukan escaping XSS, mendukung layout inheritance, dan memisahkan struktur tampilan secara jauh lebih bersih."* |
| **Q4: "Bagaimana cara kerja Accessor `harga_formatted`?"** | *"Accessor adalah fitur Eloquent untuk mengubah nilai atribut saat diakses. Dengan method `getHargaFormattedAttribute()`, kita tidak perlu memformat mata uang secara manual di view. Cukup panggil `$menu->harga_formatted` dan sistem otomatis menghasilkan format `Rp 25.000`."* |

---

## 7. PANDUAN MENJALANKAN PROJECT DI LAPTOP ANGGOTA

Bagi setiap anggota tim yang ingin menjalankan proyek ini di laptopnya masing-masing:

1. **Pastikan Laragon atau XAMPP aktif** (layanan Apache & MySQL dalam kondisi Running).
2. **Buka Terminal di direktori `laravel`:**
   ```bash
   cd laravel
   ```
3. **Pastikan dependensi vendor terpasang (jika clone dari git):**
   ```bash
   composer install
   ```
4. **Salin file konfigurasi lingkungan (jika belum ada):**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
5. **Jalankan migrasi dan seeder database:**
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Jalankan server lokal:**
   ```bash
   php artisan serve
   ```
7. **Buka peramban:**
   Akses `http://127.0.0.1:8000` di Google Chrome.

