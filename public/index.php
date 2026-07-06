<?php

require_once "../config/config.php";

$url = trim($_GET['url'] ?? '', '/');

switch ($url) {

    case '':

        require_once "../app/controllers/HomeController.php";

        $controller = new HomeController();

        $controller->index();

        break;
    
    case 'products':

        require_once "../app/controllers/ProductController.php";

        $controller = new ProductController();

        $controller->index();

        break;

    default:

        echo "<h1>404</h1>";

        echo "<p>Halaman tidak ditemukan.</p>";

}