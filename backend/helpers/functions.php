<?php

function formatRupiah(int $nominal): string
{
    return "Rp " . number_format($nominal, 0, ',', '.');
}

function saringMenu(array $menu, string $kategori = 'All', string $kataKunci = ''): array
{
    $kataKunciBersih = trim(strtolower($kataKunci));

    return array_values(array_filter($menu, function ($item) use ($kategori, $kataKunciBersih) {
        $cocokKategori = ($kategori === 'All' || empty($kategori)) || (strtolower($item['kategori']) === strtolower($kategori));
        $cocokKataKunci = empty($kataKunciBersih) || str_contains(strtolower($item['nama']), $kataKunciBersih);

        return $cocokKategori && $cocokKataKunci;
    }));
}

function saringMenuByKategori(array $menu, string $kategori): array
{
    return saringMenu($menu, $kategori);
}

function hitungTotalMenu(array $menu): int
{
    return count($menu);
}

function dapatkanDaftarKategori(array $menu): array
{
    $kategori = array_unique(array_column($menu, 'kategori'));
    array_unshift($kategori, 'All');
    return $kategori;
}
