# PANDUAN LENGKAP & MATERI PRESENTASI WEEK 9: LARAVEL CRUD & ELOQUENT ORM

Dokumen ini disusun sebagai panduan resmi anggota tim pengembang **Kape QR** untuk memahami, mendemonstrasikan, dan mempresentasikan hasil pengerjaan **Week 9: Laravel CRUD + ORM** di depan dosen dan kelas. Anggota kelompok dapat langsung membaca dokumen ini tanpa perlu penjelasan ulang secara manual.

---

## DAFTAR ISI

1. [Konteks Tugas & Latar Belakang Arsitektur](#1-konteks-tugas--latar-belakang-arsitektur)
2. [Rangkuman Jelas: Apa Saja yang Dilakukan dari 7 Permintaan Dosen](#2-rangkuman-jelas-apa-saja-yang-dilakukan-dari-7-permintaan-dosen)
3. [Struktur Folder & Standar Clean Architecture](#3-struktur-folder--standar-clean-architecture)
4. [Bedah Kode Teknis (7 Poin Implementasi)](#4-bedah-kode-teknis-7-poin-implementasi)
   - [Poin 1: Instalasi Laravel](#poin-1-instalasi-laravel)
   - [Poin 2: Konfigurasi Basis Data (.env)](#poin-2-konfigurasi-basis-data-env)
   - [Poin 3: Migrasi Multi-Tabel (Database Migrations)](#poin-3-migrasi-multi-tabel-database-migrations)
   - [Poin 4: Model & Relasi Eloquent ORM](#poin-4-model--relasi-eloquent-orm)
   - [Poin 5: Controller & Query Orchestration](#poin-5-controller--query-orchestration)
   - [Poin 6: Antarmuka Blade Responsif (Views)](#poin-6-antarmuka-blade-responsif-views)
   - [Poin 7: Routing Web (routes/web.php)](#poin-7-routing-web-routeswebphp)
5. [Cara Paling Mudah Menjalankan Project (Panduan XAMPP & Laragon)](#5-cara-paling-mudah-menjalankan-project-panduan-xampp--laragon)
6. [Skrip & Panduan Demo Presentasi di Proyektor](#6-skrip--panduan-demo-presentasi-di-proyektor)
7. [Antisipasi Pertanyaan Dosen & Kunci Jawaban](#7-antisipasi-pertanyaan-dosen--kunci-jawaban)

---

## 1. KONTEKS TUGAS & LATAR BELAKANG ARSITEKTUR

### Mengapa Week 9 Berbeda dari Week 5–7?
- **Pekan 2 s/d 4:** Kita membangun klien web murni (HTML5, Tailwind CSS, DOM JavaScript, dan Fetch API).
- **Pekan 5 s/d 7:** Kita membangun arsitektur server-side dari nol (*under-the-hood*) menggunakan PHP native prosedural, OOP, hingga Custom MVC sederhana tanpa framework. Tujuannya adalah agar kita paham bagaimana HTTP request, routing, dan class bekerja secara mendasar.
- **Pekan 9 (Pasca UTS):** Kurikulum meloncat ke **standar industri dunia nyata**, yaitu menggunakan framework enterprise: **Laravel 11**. Kita tidak lagi menulis query SQL mentah (`mysqli_query` atau manual PDO), melainkan menggunakan **Eloquent ORM** (*Object-Relational Mapping*), migrasi terstruktur, serta template engine **Blade**.

---

## 2. RANGKUMAN JELAS: APA SAJA YANG DILAKUKAN DARI 7 PERMINTAAN DOSEN

Berikut adalah rincian lengkap mengenai apa yang diminta dosen (berdasarkan catatan papan tulis perkuliahan) dan apa yang telah kita kerjakan secara nyata:

| No | Permintaan Dosen | Apa yang Kita Kerjakan? | Mengapa Dibuat Begitu? (Tujuan Rekayasa) |
| :---: | :--- | :--- | :--- |
| **1** | **Instalasi Laravel** | Menginisialisasi proyek Laravel 11 murni di folder `laravel/` menggunakan Composer & PHP 8.3, lalu membersihkan seluruh file template/boilerplate yang tidak digunakan (seperti `AGENTS.md`, `package.json`, Vite, dll). | Menghasilkan struktur aplikasi monolit standar industri yang ramping (*lean*), fungsional 100%, dan bebas dari file sampah (*zero dead-weight*). |
| **2** | **Setting Database (`.env`)** | Mengonfigurasi variabel `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, dan `DB_DATABASE=db_cafe_menu` pada berkas `laravel/.env`. | Memisahkan kredensial database dari kode program agar aman (*security best practice*) dan kompatibel langsung dengan XAMPP/Laragon lokal. |
| **3** | **Buat Table di Database $\rightarrow$ Migrations Sejumlah Table** | Merancang 2 berkas migrasi relasional: tabel `categories` (menyimpan kategori) dan tabel `menus` (menyimpan produk) yang terhubung via Foreign Key `category_id` dengan aturan `cascadeOnDelete`. Kita juga menyertakan Seeder berisi 30 menu kuliner real. | Menjamin integritas referensial data (1 kategori memiliki banyak menu) dan menyediakan version-control untuk skema database tanpa perlu create table manual. |
| **4** | **Buat Model untuk Masing-Masing Table** | Membuat class Eloquent Model `App\Models\Category` dengan relasi `hasMany(Menu::class)` dan `App\Models\Menu` dengan relasi `belongsTo(Category::class)` serta Accessor format Rupiah `getHargaFormattedAttribute()`. | Menerapkan paradigma Object-Relational Mapping (ORM) murni: database diakses sebagai Objek PHP berelasi, bukan query SQL string mentah. Dilengkapi proteksi `$fillable`. |
| **5** | **Buat Controller (Method `index`)** | Membuat `App\Http\Controllers\MenuController` dengan method `index(Request $request)` yang mengorkestrasi penarikan data kategori, query produk dengan *Eager Loading* `Menu::with('category')`, filter kategori, dan pencarian kata kunci. | Mencegah masalah performa *N+1 Query Problem*, memusatkan logika pengolahan data HTTP, dan mengirimkan data bersih siap pakai ke View. |
| **6** | **Buat Views (`index`)** | Membangun master layout `resources/views/layouts/app.blade.php` dan tampilan katalog `resources/views/menu/index.blade.php` menggunakan template engine Blade dengan mempertahankan 100% konsistensi UI/UX dari Week 2 s/d Week 7 (warna `#D5B99F`, Playfair Display, filter kategori, kartu menu, cart pill, empty state). | Menjaga konsistensi identitas visual kafe, layout inheritance yang rapi (*DRY Principle*), proteksi otomatis dari celah XSS via Blade escaping, dan pengalaman pengguna yang interaktif. |
| **7** | **Buat Route (`index`)** | Mendaftarkan route HTTP GET di `routes/web.php` yang mengarahkan path root `/` ke `MenuController@index` dengan penamaan route `menu.index`. | Menyediakan endpoint akses utama aplikasi yang terdefinisi secara deklaratif dan mudah dipanggil di seluruh view menggunakan helper `route('menu.index')`. |

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
│   │       ├── Controller.php         <- Base Controller abstrak
│   │       └── MenuController.php     <- Poin 5: Eager loading, filter, & search
│   ├── Models/
│   │   ├── Category.php               <- Poin 4: Relasi hasMany(Menu::class)
│   │   └── Menu.php                   <- Poin 4: Relasi belongsTo(Category::class) & Accessor
│   └── Providers/
│       └── AppServiceProvider.php     <- Service provider aplikasi
├── bootstrap/
│   └── app.php                        <- Inisialisasi framework & routing Laravel 11
├── config/                            <- Konfigurasi framework (database, app, session, dll)
├── database/
│   ├── migrations/
│   │   ├── ..._create_categories_table.php  <- Poin 3: Skema tabel kategori
│   │   └── ..._create_menus_table.php       <- Poin 3: Skema tabel menu + foreignId
│   └── seeders/
│       ├── CategorySeeder.php         <- Seeder 3 kategori utama
│       ├── MenuSeeder.php             <- Seeder 30 data menu kuliner real
│       └── DatabaseSeeder.php         <- Seeder runner utama
├── public/
│   ├── asset/                         <- Aset gambar kuliner (24 file foto)
│   ├── .htaccess & favicon.ico
│   └── index.php                      <- Entry point HTTP server
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          <- Poin 6: Master layout Blade + Tailwind CDN
│       └── menu/
│           └── index.blade.php        <- Poin 6: Katalog menu dinamis + filter
├── routes/
│   ├── console.php                    <- Console commands
│   └── web.php                        <- Poin 7: Route::get('/', [MenuController::class, 'index'])
├── tests/
│   ├── TestCase.php
│   ├── Feature/ExampleTest.php        <- Feature test HTTP response & database
│   └── Unit/MenuTest.php              <- Unit test logika model Menu
├── .env & .env.example                <- Poin 2: Konfigurasi MySQL
├── artisan                            <- CLI runner Laravel
├── composer.json & lock               <- Dependensi backend murni
└── README.md                          <- Dokumentasi resmi Cafe QR Menu
```

---

## 4. BEDAH KODE TEKNIS (7 POIN IMPLEMENTASI)

### Poin 1: Instalasi Laravel
Laravel 11 diinisialisasi menggunakan PHP 8.3 dan Composer dengan arsitektur modern (*lean skeleton*). Seluruh dependensi Node/Vite dan komentar boilerplate telah dibersihkan sehingga repositori berbobot ringan dan fungsional.

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

## 5. CARA PALING MUDAH MENJALANKAN PROJECT (PANDUAN XAMPP & LARAGON)

Ikuti 5 langkah simpel ini jika ingin menjalankan project di laptop sendiri atau di komputer lain:

### Opsi A: Menjalankan Menggunakan XAMPP (Cara Paling Cepat & Mudah)

1. **Jalankan Apache & MySQL di XAMPP:**
   - Buka **XAMPP Control Panel**.
   - Klik tombol **Start** pada **Apache** dan **MySQL** (keduanya menjadi hijau).

2. **Buat Database di phpMyAdmin:**
   - Buka browser (Chrome), buka: `http://localhost/phpmyadmin`
   - Klik menu **Databases** (atau **Basis data**).
   - Di kolom nama database, ketik: `db_cafe_menu` lalu klik tombol **Create**.

3. **Buka Terminal Melalui Tombol "Shell" XAMPP (Tips Anti-Ribet):**
   - Di XAMPP Control Panel, klik tombol **"Shell"** di sebelah kanan.
   - *(Keuntungan: Terminal bawaan XAMPP ini sudah otomatis mengenali perintah `php`, `composer`, dan `artisan` tanpa perlu repot mengatur PATH lingkungan Windows!)*
   - Di jendela terminal yang muncul, masuk ke folder `laravel`:
     ```bash
     cd "d:\perkuliahan_semester_5\tugas pemrograman web\laravel"
     ```

4. **Jalankan Migrasi & Seeder Database (Cukup 1 Baris Perintah):**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Tabel `categories`, `menus`, beserta 30 menu kuliner real langsung otomatis terisi ke MySQL XAMPP.*

5. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Buka browser di: **`http://127.0.0.1:8000`**  
   *(Web Kape QR langsung menyala dan siap didemokan!)*

---

### Opsi B: Menjalankan Menggunakan Laragon

1. Buka aplikasi **Laragon**, lalu klik tombol **Start All**.
2. Buka terminal (klik kanan di Laragon $\rightarrow$ *Laragon* $\rightarrow$ *Terminal* atau buka PowerShell).
3. Masuk ke direktori `laravel`:
   ```bash
   cd "d:\perkuliahan_semester_5\tugas pemrograman web\laravel"
   ```
4. Jalankan perintah migrasi dan server:
   ```bash
   php artisan migrate:fresh --seed
   php artisan serve
   ```
5. Buka peramban di: **`http://127.0.0.1:8000`**.

---

## 6. SKRIP & PANDUAN DEMO PRESENTASI DI PROYEKTOR

Gunakan panduan 4 langkah berikut saat maju mempresentasikan tugas di depan kelas:

### Langkah 1: Membuka Visual Studio Code
Buka folder `laravel/` di VS Code (`File -> Open Folder -> laravel`).
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
> *Ketika kita memilih kategori atau mengetik kata kunci pencarian, Controller secara cerdas memfilter kueri Eloquent dan memperbarui tampilan secara instan. Kami juga mempertahankan interaktivitas tombol Order Now dan floating summary pill yang menghitung pesanan secara langsung."*

---

## 7. ANTISIPASI PERTANYAAN DOSEN & KUNCI JAWABAN

| Pertanyaan Dosen | Kunci Jawaban Lengkap & Benar |
| :--- | :--- |
| **Q1: "Mengapa kalian memakai `with('category')` di Controller, kenapa tidak langsung `Menu::all()`?"** | *"Jika kita memakai `Menu::all()` lalu di view memanggil `$menu->category->nama_kategori`, Laravel akan melakukan 1 query SQL baru untuk setiap kartu menu. Untuk 30 menu, itu berarti 31 query (N+1 Query Problem). Dengan `with('category')`, Laravel melakukan Eager Loading sehingga hanya mengeksekusi 2 query efisien di awal."* |
| **Q2: "Apa fungsi `$fillable` pada Model `Menu` dan `Category`?"** | *"`$fillable` adalah whitelist atribut yang diizinkan untuk diisi secara massal (Mass Assignment). Ini adalah fitur keamanan penting di Laravel untuk mencegah serangan Mass Assignment Vulnerability, di mana pengguna berbahaya mencoba menyuntikkan kolom terlarang (seperti ID atau role admin)."* |
| **Q3: "Apa perbedaan render tampilan di Week 7 (MVC Native) dan Week 9 (Laravel)?"** | *"Di Week 7, kita merender HTML menggunakan PHP tag `<?= $menu->renderKartu(); ?>` secara manual. Di Week 9, kita menggunakan template engine Blade (`@extends`, `@section`, `@forelse`) yang otomatis melakukan escaping XSS, mendukung layout inheritance, dan memisahkan struktur tampilan secara jauh lebih bersih."* |
| **Q4: "Bagaimana cara kerja Accessor `harga_formatted`?"** | *"Accessor adalah fitur Eloquent untuk mengubah nilai atribut saat diakses. Dengan method `getHargaFormattedAttribute()`, kita tidak perlu memformat mata uang secara manual di view. Cukup panggil `$menu->harga_formatted` dan sistem otomatis menghasilkan format `Rp 25.000`."* |
| **Q5: "Kenapa skema database kalian hanya 2 tabel dan tidak ada tabel `users` atau `cache`?"** | *"Karena kami mematuhi prinsip Clean Architecture. Berdasarkan instruksi tugas pekan 9 yang berfokus pada katalog menu dan relasi multi-tabel, kami merancang skema yang tepat sasaran: `categories` dan `menus`. Tabel boilerplate yang tidak terpakai dibersihkan agar database murni merepresentasikan domain bisnis kafe."* |
