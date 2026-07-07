<?php

require_once "../config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = trim($_GET['url'] ?? '', '/');
$method = $_SERVER['REQUEST_METHOD'];

switch ($url) {

    case '':

        require_once "../app/controllers/HomeController.php";

        $controller = new HomeController();

        $controller->index();

        break;

    //==================================================
    case 'products':
    
        require_once "../app/controllers/ProductController.php";
    
        $controller = new ProductController();
    
        $controller->index();
    
        break;

    //==================================================
    case 'login':
    
        require_once "../app/controllers/AuthController.php";
    
        $controller = new AuthController();
    
            // jika metode request adalah POST, maka panggil method login()
            // jika metode request adalah GET, maka panggil method showLogin()
            if ($method === 'POST') {
                $controller->login();
            } else {
                $controller->showLogin();
            }
    
        break;

    //==================================================
    case 'register':
        require_once "../app/controllers/AuthController.php";
        $controller = new AuthController();
        // jika metode request adalah POST, maka panggil method register()
        // jika metode request adalah GET, maka panggil method showRegister()
        if ($method === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    //==================================================
    case 'logout':
        require_once "../app/controllers/AuthController.php";
        $controller = new AuthController();
        $controller->logout();
        break;

    //==================================================
    default:
        // Cek apakah URL diawali dengan products/
        if (preg_match('#^products/(.+)$#', $url, $matches)) {
            require_once "../app/controllers/ProductController.php";
            $controller = new ProductController();
            $controller->detail($matches[1]);
            break;
        }

        echo "<h1>404</h1>";

        echo "<p>Halaman tidak ditemukan.</p>";

}