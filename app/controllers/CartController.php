<?php

require_once "../app/models/CartModel.php";
require_once "../app/models/ProductModel.php";

class CartController
{
    private $cartModel;
    private $productModel;

    //------------------------------------------------
    // membuat objek model
    //------------------------------------------------
    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->productModel = new ProductModel();
    }

    //------------------------------------------------
    // menampilkan halaman keranjang
    //------------------------------------------------
    public function index()
    {
        // User harus login
        if (!isset($_SESSION['user'])) {

            $_SESSION['error'] = "Silakan login terlebih dahulu.";

            header("Location: " . BASE_URL . "/login");

            exit;
        }

        $userId = $_SESSION['user']['id'];

        $cart = $this->cartModel->getCartByUser($userId);

        $items = [];

        if ($cart) {

            $items = $this->cartModel->getCartItems($cart['id']);

        }

        require_once "../app/views/cart/index.php";
    }

    //------------------------------------------------
    // menambahkan produk ke keranjang
    //------------------------------------------------
    public function add()
    {
        // Harus login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $userId = $_SESSION['user']['id'];
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);
        // validasi jumlah pembelian
        if ($quantity <= 0) {
            $_SESSION['error'] = "Jumlah produk tidak valid.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }
            if ($productId <= 0) {
        $_SESSION['error'] = "Produk tidak valid.";
        header("Location: " . BASE_URL . "/products");
        exit;
    }
    $product = $this->productModel->getProductById($productId);
    if (!$product) {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        header("Location: " . BASE_URL . "/products");
        exit;
    }
    
    // cek apakah user sudah memiliki cart
    $cart = $this->cartModel->getCartByUser($userId);
    if (!$cart) {
        $cartId = $this->cartModel->createCart($userId);
    } else {
        $cartId = $cart['id'];
    }
    
    // cek apakah produk sudah ada di cart
    $item = $this->cartModel->findCartItem(
        $cartId,
        $productId
    );
    
    // validasi stok produk
    $currentQuantity = $item ? $item['quantity'] : 0;
    $totalQuantity = $currentQuantity + $quantity;
    if ($totalQuantity > $product['stock']) {
        $_SESSION['error'] =
            "Jumlah produk melebihi stok yang tersedia.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // jika produk sudah ada
    if ($item) {
        $newQuantity = $item['quantity'] + $quantity;
        $subtotal = $newQuantity * $item['price'];
        $this->cartModel->updateQuantity(
            $item['id'],
            $newQuantity,
            $subtotal
        );
    } else {
        $data = [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product['price'],
            'subtotal' => $product['price'] * $quantity
        ];
        $this->cartModel->addCartItem($data);
    }
    
    // redirect
    $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
    }

    //------------------------------------------------
    // memperbarui jumlah produk pada keranjang
    //------------------------------------------------
    public function update()
    {
        // User harus login
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        if ($cartItemId <= 0) {
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        // Ambil data item keranjang
        $item = $this->cartModel->getCartItemById($cartItemId);
        if (!$item) {
            $_SESSION['error'] = "Produk tidak ditemukan.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        // Ambil data produk
        $product = $this->productModel->getProductById($item['product_id']);
        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        $quantity = $item['quantity'];
        if ($action === "plus") {
            $quantity++;
        } elseif ($action === "minus") {
            $quantity--;
        }
        // Minimal quantity = 1
        if ($quantity < 1) {
            $quantity = 1;
        }
        // Tidak boleh melebihi stok
        if ($quantity > $product['stock']) {
            $_SESSION['error'] = "Stok produk tidak mencukupi.";
            header("Location: " . BASE_URL . "/cart");
            exit;
        }
        $subtotal = $quantity * $item['price'];
        $this->cartModel->updateQuantity(
            $cartItemId,
            $quantity,
            $subtotal
        );
        header("Location: " . BASE_URL . "/cart");
        exit;
    }

    //------------------------------------------------
    // menghapus produk dari keranjang
    //------------------------------------------------
    public function remove()
    {
        // User harus login
        if (!isset($_SESSION['user'])) {

            $_SESSION['error'] = "Silakan login terlebih dahulu.";

            header("Location: " . BASE_URL . "/login");

            exit;
        }

        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);

        if ($cartItemId <= 0) {

            $_SESSION['error'] = "Produk tidak valid.";

            header("Location: " . BASE_URL . "/cart");

            exit;
        }

        // Pastikan item memang ada
        $item = $this->cartModel->getCartItemById($cartItemId);

        if (!$item) {

            $_SESSION['error'] = "Produk tidak ditemukan.";

            header("Location: " . BASE_URL . "/cart");

            exit;
        }

        $this->cartModel->deleteCartItem($cartItemId);

        $_SESSION['success'] = "Produk berhasil dihapus dari keranjang.";

        header("Location: " . BASE_URL . "/cart");

        exit;
    }

}