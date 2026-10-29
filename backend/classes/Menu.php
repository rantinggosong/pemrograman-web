<?php

class Menu
{
    private int $id;
    private string $nama;
    private string $gambar;
    private int $harga;
    private string $kategori;
    private string $status;

    public function __construct(
        int $id,
        string $nama,
        string $gambar,
        int $harga,
        string $kategori,
        string $status
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->gambar = $gambar;
        $this->harga = $harga;
        $this->kategori = $kategori;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getGambar(): string
    {
        return $this->gambar;
    }

    public function getHarga(): int
    {
        return $this->harga;
    }

    public function getKategori(): string
    {
        return $this->kategori;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getHargaFormatted(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function isBestSeller(): bool
    {
        return strtolower($this->status) === 'best seller';
    }

    public function isPromo(): bool
    {
        return strtolower($this->status) === 'promo';
    }

    public function isHabis(): bool
    {
        return strtolower($this->status) === 'habis';
    }

    public function getProfile(): array
    {
        return [
            'id'       => $this->id,
            'nama'     => $this->nama,
            'kategori' => $this->kategori,
            'harga'    => $this->getHargaFormatted(),
            'status'   => $this->status,
        ];
    }

    public function renderKartu(): string
    {
        $nama   = htmlspecialchars($this->nama);
        $gambar = htmlspecialchars($this->gambar);
        $harga  = $this->getHargaFormatted();

        $badgeHtml = '';
        if ($this->isBestSeller()) {
            $badgeHtml = '<span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-[#D5B99F] text-[#1A110A] px-2 py-0.5 rounded-full shadow">' . htmlspecialchars($this->status) . '</span>';
        } elseif ($this->isPromo()) {
            $badgeHtml = '<span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-amber-400 text-[#1A110A] px-2 py-0.5 rounded-full shadow">' . htmlspecialchars($this->status) . '</span>';
        } elseif ($this->isHabis()) {
            $badgeHtml = '<span class="absolute top-2 left-2 text-[10px] md:text-xs font-bold bg-red-500 text-white px-2 py-0.5 rounded-full shadow">' . htmlspecialchars($this->status) . '</span>';
        }

        $opacityClass   = $this->isHabis() ? 'opacity-60' : '';
        $buttonDisabled = $this->isHabis() ? 'disabled cursor-not-allowed opacity-50' : '';
        $buttonLabel    = $this->isHabis() ? 'Habis' : "Order Now ({$harga})";

        return <<<HTML
        <div class="flex flex-col bg-white/30 border border-[#1A110A]/10 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow backdrop-blur-sm {$opacityClass}">
            <div class="bg-[#1A110A] p-2 md:p-4 relative">
                {$badgeHtml}
                <div class="w-full aspect-square rounded-xl overflow-hidden shadow-inner">
                    <img src="{$gambar}" alt="{$nama}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
            </div>
            <div class="flex flex-col items-center p-3 md:p-6 text-center">
                <h3 class="font-serif text-base md:text-2xl font-bold text-[#1A110A] mb-3 md:mb-6 leading-tight">{$nama}</h3>
                <button class="w-full py-1.5 px-2 md:py-3 md:px-4 text-xs md:text-base bg-transparent border md:border-2 border-[#1A110A]/30 text-[#1A110A] font-semibold rounded-lg md:rounded-xl hover:bg-[#1A110A] hover:text-[#D5B99F] transition-all duration-300 {$buttonDisabled}">
                    {$buttonLabel}
                </button>
            </div>
        </div>
        HTML;
    }
}