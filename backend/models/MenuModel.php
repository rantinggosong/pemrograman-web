<?php

require_once __DIR__ . '/../data/data_menu.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../classes/Menu.php';

class MenuModel
{
    private array $sumberData;

    public function __construct()
    {
        global $daftarMenu;
        $this->sumberData = $daftarMenu;
    }

    public function ambilSemua(string $kategori = 'All', string $kataKunci = ''): array
    {
        $hasilFilter = saringMenu($this->sumberData, $kategori, $kataKunci);

        return array_map(function (array $item): Menu {
            return new Menu(
                $item['id'],
                $item['nama'],
                $item['gambar'],
                $item['harga'],
                $item['kategori'],
                $item['status']
            );
        }, $hasilFilter);
    }

    public function cariById(int $id): ?Menu
    {
        foreach ($this->sumberData as $item) {
            if ($item['id'] === $id) {
                return new Menu(
                    $item['id'],
                    $item['nama'],
                    $item['gambar'],
                    $item['harga'],
                    $item['kategori'],
                    $item['status']
                );
            }
        }

        return null;
    }

    public function daftarKategori(): array
    {
        return dapatkanDaftarKategori($this->sumberData);
    }

    public function hitungTotal(string $kategori = 'All', string $kataKunci = ''): int
    {
        return count($this->ambilSemua($kategori, $kataKunci));
    }
}