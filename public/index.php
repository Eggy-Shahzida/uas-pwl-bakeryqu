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
    case 'cart':
        require_once "../app/controllers/CartController.php";
        $controller = new CartController();
        $controller->index();
        break;

    //==================================================
    case 'cart/add':
        require_once "../app/controllers/CartController.php";
        $controller = new CartController();
        $controller->add();
        break;

    //==================================================
    case 'cart/update':
        require_once "../app/controllers/CartController.php";
        $controller = new CartController();
        if ($method === 'POST') {
            $controller->update();
        }
        break;

    //==================================================
    case 'cart/remove':
        require_once "../app/controllers/CartController.php";
        $controller = new CartController();
        if ($method === 'POST') {
            $controller->remove();
        }
        break;

    //==================================================
    case 'checkout':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        if ($method === 'POST') {
            $controller->process();
        } else {
            $controller->index();
        }
        break;

    //==================================================
    // AJAX step 2: kota/kabupaten berdasarkan provinsi
    case 'checkout/get-cities':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        $controller->getCities();
        break;
 
    //==================================================
    // AJAX step 3: kecamatan berdasarkan kota
    case 'checkout/get-districts':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        $controller->getDistricts();
        break;
 
    //==================================================
    // AJAX: hitung ongkos kirim
    case 'checkout/get-cost':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        if ($method === 'POST') {
            $controller->getCost();
        }
        break;

    //==================================================
    // menampilkan halaman review sebelum konfirmasi pesanan
    case 'checkout/review':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        $controller->review();
        break;
 
    //==================================================
    // konfirmasi pesanan final (placeholder, lihat TODO di controller)
    case 'checkout/confirm':
        require_once "../app/controllers/CheckoutController.php";
        $controller = new CheckoutController();
        if ($method === 'POST') {
            $controller->confirm();
        }
        break;

    //==================================================
    // daftar pesanan milik user ("Pesanan Saya")
    case 'order':
        require_once "../app/controllers/OrderController.php";
        $controller = new OrderController();
        $controller->index();
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
 
        // Cek apakah URL diawali dengan order/{id}
        if (preg_match('#^order/(\d+)$#', $url, $matches)) {
            require_once "../app/controllers/OrderController.php";
            $controller = new OrderController();
            $controller->detail($matches[1]);
            break;
        }

        echo "<h1>404</h1>";

        echo "<p>Halaman tidak ditemukan.</p>";

}