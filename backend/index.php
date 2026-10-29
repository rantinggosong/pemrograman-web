<?php

require_once __DIR__ . '/controllers/MenuController.php';

$controller = new MenuController();
$action = $_GET['action'] ?? 'index';

if ($action === 'show') {
    $controller->show();
} else {
    $controller->index();
}