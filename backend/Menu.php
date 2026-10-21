<?php
/**
 * Class Menu
 * 
 * Model OOP untuk merepresentasikan item menu restoran/cafe
 * Mengimplementasikan prinsip Object-Oriented Programming (OOP)
 */
class Menu
{
    // 1. Properti Class (Sesuai spesifikasi tugas)
    public int $id;
    public string $nama;
    public string $kategori;
    public int $harga;
    public string $status;
    public string $gambar;

    /**
     * 2. Constructor untuk inisialisasi data objek Menu
     *
     * @param int $id
     * @param string $nama
     * @param string $kategori
     * @param int $harga
     * @param string $status
     * @param string $gambar
     */
    public function __construct(
        int $id,
        string $nama,
        string $kategori,
        int $harga,
        string $status = 'Tersedia',
        string $gambar = ''
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->harga = $harga;
        $this->status = $status;
        $this->gambar = $gambar;
    }

    /**
     * 3. Method getHargaRupiah()
     * Mengembalikan string harga terformat (contoh: Rp 18.000)
     *
     * @return string
     */
    public function getHargaRupiah(): string
    {
        return "Rp " . number_format($this->harga, 0, ',', '.');
    }

    /**
     * 4. Method getStatusBadge()
     * Mengembalikan HTML badge Tailwind untuk status ketersediaan menu
     *
     * @return string
     */
    public function getStatusBadge(): string
    {
        $statusClean = strtolower(trim($this->status));

        switch ($statusClean) {
            case 'best seller':
                return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-bold bg-[#D5B99F]/80 text-[#1A110A] border border-[#1A110A]/20 backdrop-blur-sm shadow-sm">★ Best Seller</span>';

            case 'promo':
                return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-bold bg-amber-500/20 text-amber-900 border border-amber-600/30 backdrop-blur-sm shadow-sm">🔥 Promo</span>';

            case 'habis':
                return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-semibold bg-rose-500/20 text-rose-900 border border-rose-500/30 backdrop-blur-sm shadow-sm">✕ Habis</span>';

            case 'tersedia':
            default:
                return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] md:text-xs font-semibold bg-emerald-500/20 text-emerald-900 border border-emerald-500/30 backdrop-blur-sm shadow-sm">✔ Tersedia</span>';
        }
    }

    /**
     * 5. Static Method getAllFromJSON()
     * Membaca data dari backend/menu.json dan mengubah tiap item menjadi instance Object Menu.
     * Jika file JSON tidak ditemukan atau kosong, sediakan array default berisi kumpulan Object new Menu(...).
     *
     * @param string $filepath Path ke file menu.json
     * @return Menu[] Array of Menu objects
     */
    public static function getAllFromJSON(string $filepath): array
    {
        $listMenu = [];

        if (!file_exists($filepath)) {
            // File tidak ditemukan, langsung ke fallback
            return self::getDefaultMenu();
        } elseif (($jsonContent = @file_get_contents($filepath)) === false) {
            // File ada tapi gagal dibaca
            return self::getDefaultMenu();
        } elseif (!is_array($decoded = json_decode($jsonContent, true)) || count($decoded) === 0) {
            // JSON tidak valid atau kosong
            return self::getDefaultMenu();
        } else {
            // Semua kondisi terpenuhi — proses data JSON
            foreach ($decoded as $item) {
                $listMenu[] = new self(
                    (int)($item['id'] ?? 0),
                    (string)($item['nama'] ?? 'Tanpa Nama'),
                    (string)($item['kategori'] ?? 'Umum'),
                    (int)($item['harga'] ?? 0),
                    (string)($item['status'] ?? 'Tersedia'),
                    (string)($item['gambar'] ?? './asset/nasi.jpeg')
                );
            }
            return $listMenu;
        }

        // Fallback jika file JSON tidak ditemukan atau gagal di-parse
        return self::getDefaultMenu();
    }

    /**
     * Helper Static Method: Menyediakan data default kumpulan Objek Menu
     *
     * @return Menu[]
     */
    public static function getDefaultMenu(): array
    {
        return [
            new self(1, "White Coffie", "Minuman", 25000, "Best Seller", "./asset/white coffie.jpeg"),
            new self(2, "Americano", "Minuman", 20000, "Tersedia", "./asset/images.jpeg"),
            new self(3, "Espresso", "Minuman", 18000, "Promo", "./asset/Espresso.jpeg"),
            new self(11, "Nasi Goreng Spesial", "Makanan", 35000, "Best Seller", "./asset/nasi.jpeg"),
            new self(12, "Mie Tek-Tek Goreng", "Makanan", 25000, "Tersedia", "./asset/Mie Tek-Tek Goreng.jpeg"),
            new self(21, "French Fries", "Snack", 15000, "Tersedia", "./asset/French Fries.jpeg"),
            new self(23, "Mozzarella Sticks", "Snack", 25000, "Best Seller", "./asset/Mozzarella Sticks.jpeg"),
            new self(27, "Churros Chocolate", "Snack", 22000, "Promo", "./asset/Churros Chocolate.jpeg"),
        ];
    }
}
