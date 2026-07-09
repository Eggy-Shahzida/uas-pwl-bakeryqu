<?php

require_once "../app/models/CartModel.php";
require_once "../app/models/ProductModel.php";
require_once "../app/models/OrderModel.php";
require_once "../app/service/ShippingService.php";

class CheckoutController
{
    private $cartModel;

    private $productModel;

    private $orderModel;

    private $shippingService;

    //------------------------------------------------
    // membuat objek model & service
    //------------------------------------------------
    public function __construct()
    {
        $this->cartModel = new CartModel();

        $this->productModel = new ProductModel();

        $this->orderModel = new OrderModel();

        $this->shippingService = new ShippingService();
    }

    //------------------------------------------------
    // menampilkan halaman checkout
    //------------------------------------------------
    public function index()
    {
        // User wajib login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Ambil cart milik user
        $cart = $this->cartModel->getCartByUser($userId);

        // Jika belum punya cart
        if (!$cart) {
            $_SESSION['error'] = "Keranjang masih kosong.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }

        // Ambil seluruh item
        $items = $this->cartModel->getCartItems($cart['id']);

        // Jika tidak ada item
        if (empty($items)) {
            $_SESSION['error'] = "Keranjang masih kosong.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }

        // Hitung subtotal
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
        }

        // Ambil daftar provinsi untuk dropdown pertama (step 1)
        $provinceResult = $this->shippingService->getProvinces();

        $provinces = $provinceResult['success'] ? $provinceResult['data'] : [];

        if (!$provinceResult['success']) {
            $_SESSION['error'] = "Gagal memuat data provinsi: " . $provinceResult['message'];
        }

        require_once "../app/views/checkout/index.php";
    }

    //------------------------------------------------
    // AJAX (step 2): ambil kota/kabupaten berdasarkan provinsi
    //------------------------------------------------
    public function getCities()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.', 'data' => []]);
            exit;
        }

        $provinceId = (int) ($_GET['province_id'] ?? 0);

        if ($provinceId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Provinsi tidak valid.', 'data' => []]);
            exit;
        }

        $result = $this->shippingService->getCities($provinceId);

        echo json_encode($result);

        exit;
    }

    //------------------------------------------------
    // AJAX (step 3): ambil kecamatan berdasarkan kota
    //------------------------------------------------
    public function getDistricts()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.', 'data' => []]);
            exit;
        }

        $cityId = (int) ($_GET['city_id'] ?? 0);

        if ($cityId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Kota/kabupaten tidak valid.', 'data' => []]);
            exit;
        }

        $result = $this->shippingService->getDistricts($cityId);

        echo json_encode($result);

        exit;
    }

    //------------------------------------------------
    // AJAX: hitung ongkos kirim setelah kecamatan dipilih
    //------------------------------------------------
    public function getCost()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.', 'data' => []]);
            exit;
        }

        $districtId = (int) ($_POST['district_id'] ?? 0);

        $courier = $_POST['courier'] ?? 'jne';

        if ($districtId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Kecamatan tujuan tidak valid.', 'data' => []]);
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $cart = $this->cartModel->getCartByUser($userId);

        $items = $cart ? $this->cartModel->getCartItems($cart['id']) : [];

        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang kosong.', 'data' => []]);
            exit;
        }

        // Hitung total berat (gram). Catatan: kolom 'weight' belum ada
        // di ProductModel/CartModel, sementara pakai default 500gr/produk.
        $totalWeight = 0;

        foreach ($items as $item) {
            $itemWeight = isset($item['weight']) ? (int) $item['weight'] : 500;
            $totalWeight += $itemWeight * $item['quantity'];
        }

        if ($totalWeight <= 0) {
            $totalWeight = 1000;
        }

        $result = $this->shippingService->calculateCost($districtId, $totalWeight, $courier);

        echo json_encode($result);

        exit;
    }

    //------------------------------------------------
    // memproses submit form checkout -> simpan ke session -> redirect ke review
    //------------------------------------------------
    public function process()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Pastikan cart masih ada isinya
        $cart = $this->cartModel->getCartByUser($userId);

        $items = $cart ? $this->cartModel->getCartItems($cart['id']) : [];

        if (empty($items)) {
            $_SESSION['error'] = "Keranjang masih kosong.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }

        // Ambil & bersihkan input
        $receiverName = trim($_POST['receiver_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $provinceId = (int) ($_POST['province_id'] ?? 0);
        $provinceName = trim($_POST['province_name'] ?? '');
        $cityId = (int) ($_POST['city_id'] ?? 0);
        $cityName = trim($_POST['city_name'] ?? '');
        $districtId = (int) ($_POST['district_id'] ?? 0);
        $districtName = trim($_POST['district_name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $note = trim($_POST['note'] ?? '');
        $courier = trim($_POST['courier'] ?? 'jne');
        $shippingCost = (int) ($_POST['shipping_cost'] ?? 0);
        $shippingService = trim($_POST['shipping_service'] ?? '');

        // Validasi field wajib
        $errors = [];

        if ($receiverName === '') {
            $errors[] = "Nama penerima wajib diisi.";
        }

        if (!preg_match('/^[0-9]{9,15}$/', $phone)) {
            $errors[] = "Nomor telepon tidak valid (9-15 digit angka).";
        }

        if ($provinceId <= 0) {
            $errors[] = "Provinsi wajib dipilih.";
        }

        if ($cityId <= 0) {
            $errors[] = "Kota/Kabupaten wajib dipilih.";
        }

        if ($districtId <= 0) {
            $errors[] = "Kecamatan wajib dipilih.";
        }

        if ($address === '') {
            $errors[] = "Alamat lengkap wajib diisi.";
        }

        if ($shippingCost <= 0) {
            $errors[] = "Ongkos kirim belum dihitung. Silakan pilih ulang kecamatan tujuan.";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            $_SESSION['old'] = $_POST;
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }

        // Simpan data checkout sementara ke session (belum jadi order)
        $_SESSION['checkout_data'] = [
            'receiver_name' => $receiverName,
            'phone' => $phone,
            'province_id' => $provinceId,
            'province_name' => $provinceName,
            'city_id' => $cityId,
            'city_name' => $cityName,
            'district_id' => $districtId,
            'district_name' => $districtName,
            'address' => $address,
            'note' => $note,
            'courier' => $courier,
            'shipping_cost' => $shippingCost,
            'shipping_service' => $shippingService,
        ];

        unset($_SESSION['old']);

        header("Location: " . BASE_URL . "/checkout/review");
        exit;
    }

    //------------------------------------------------
    // menampilkan halaman review sebelum konfirmasi pesanan
    //------------------------------------------------
    public function review()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        // Data alamat & ongkir harus sudah diisi lewat form checkout
        if (empty($_SESSION['checkout_data'])) {
            $_SESSION['error'] = "Silakan lengkapi data pengiriman terlebih dahulu.";
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }

        $checkoutData = $_SESSION['checkout_data'];

        $userId = $_SESSION['user']['id'];

        $cart = $this->cartModel->getCartByUser($userId);

        $items = $cart ? $this->cartModel->getCartItems($cart['id']) : [];

        if (empty($items)) {
            $_SESSION['error'] = "Keranjang masih kosong.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }

        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
        }

        $grandTotal = $subtotal + $checkoutData['shipping_cost'];

        require_once "../app/views/checkout/review.php";
    }

    //------------------------------------------------
    // konfirmasi pesanan final: simpan order + order_items,
    // kurangi stok, kosongkan cart, lalu arahkan ke detail pesanan
    //------------------------------------------------
    public function confirm()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        if (empty($_SESSION['checkout_data'])) {
            $_SESSION['error'] = "Silakan lengkapi data pengiriman terlebih dahulu.";
            header("Location: " . BASE_URL . "/checkout");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $checkoutData = $_SESSION['checkout_data'];

        $cart = $this->cartModel->getCartByUser($userId);

        $items = $cart ? $this->cartModel->getCartItems($cart['id']) : [];

        if (empty($items)) {
            $_SESSION['error'] = "Keranjang masih kosong.";
            header("Location: " . BASE_URL . "/products");
            exit;
        }

        // Hitung ulang subtotal & total di server (jangan percaya nilai dari client)
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['subtotal'];
        }

        $total = $subtotal + $checkoutData['shipping_cost'];

        // 1) Buat order
        $orderId = $this->orderModel->createOrder([
            'order_code' => $this->orderModel->generateOrderCode(),
            'user_id' => $userId,
            'receiver_name' => $checkoutData['receiver_name'],
            'phone' => $checkoutData['phone'],
            'province_id' => $checkoutData['province_id'],
            'province_name' => $checkoutData['province_name'],
            'city_id' => $checkoutData['city_id'],
            'city_name' => $checkoutData['city_name'],
            'district_id' => $checkoutData['district_id'],
            'district_name' => $checkoutData['district_name'],
            'address' => $checkoutData['address'],
            'note' => $checkoutData['note'] ?? '',
            'courier' => $checkoutData['courier'],
            'service' => $checkoutData['shipping_service'],
            'shipping_cost' => $checkoutData['shipping_cost'],
            'subtotal' => $subtotal,
            'total' => $total,
            'status' => 'pending',
        ]);

        // 2) Simpan setiap item + kurangi stok produk terkait
        foreach ($items as $item) {

            $product = $this->productModel->getProductById($item['product_id']);

            $this->orderModel->addOrderItem([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'product_image' => $product['image'] ?? null,
                'product_weight' => $product['weight'] ?? 500,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);

            $this->productModel->decreaseStock($item['product_id'], $item['quantity']);
        }

        // 3) Kosongkan cart user
        $this->cartModel->clearCart($cart['id']);

        // 4) Bersihkan data sementara checkout
        unset($_SESSION['checkout_data']);

        $_SESSION['success'] = "Pesanan berhasil dibuat!";

        header("Location: " . BASE_URL . "/order/" . $orderId);
        exit;
    }
}