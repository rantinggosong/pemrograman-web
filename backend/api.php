<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/data/data_menu.php';
require_once __DIR__ . '/helpers/functions.php';

$kategori = $_GET['kategori'] ?? 'All';
$menuTersaring = saringMenuByKategori($daftarMenu, $kategori);

echo json_encode($menuTersaring, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
