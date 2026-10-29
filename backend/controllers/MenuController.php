<?php

require_once __DIR__ . '/../models/MenuModel.php';

class MenuController
{
    private MenuModel $model;

    public function __construct()
    {
        $this->model = new MenuModel();
    }

    public function index(): void
    {
        $kategoriAktif = $_GET['kategori'] ?? 'All';
        $kataKunci     = trim($_GET['q'] ?? '');

        $daftarMenu    = $this->model->ambilSemua($kategoriAktif, $kataKunci);
        $totalItem     = $this->model->hitungTotal($kategoriAktif, $kataKunci);
        $daftarKategori = $this->model->daftarKategori();

        $namaToko  = 'Menu';
        $sloganToko = 'Pesan langsung dari meja kamu';

        require_once __DIR__ . '/../views/menu/index.php';
    }

    public function show(): void
    {
        $id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $menu = $this->model->cariById($id);

        $namaToko   = 'Menu';
        $sloganToko = 'Pesan langsung dari meja kamu';

        require_once __DIR__ . '/../views/menu/show.php';
    }
}