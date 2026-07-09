<?php

require_once "../app/models/OrderModel.php";
require_once "../app/models/ProductModel.php";
require_once "../app/models/UserModel.php";

class AdminController
{
    private $orderModel;

    private $productModel;

    private $userModel;

    //------------------------------------------------
    // membuat objek model & pastikan yang akses adalah admin
    //------------------------------------------------
    public function __construct()
    {
        // User wajib login sebagai admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $this->orderModel = new OrderModel();

        $this->productModel = new ProductModel();

        $this->userModel = new UserModel();
    }

    //------------------------------------------------
    // menampilkan dashboard admin
    //------------------------------------------------
    public function index()
    {
        // Ringkasan pesanan
        $totalOrders = $this->orderModel->countAll();

        $pendingOrders = $this->orderModel->countByStatus('pending');

        $processingOrders = $this->orderModel->countByStatus('processing');

        $shippingOrders = $this->orderModel->countByStatus('shipping');

        $completedOrders = $this->orderModel->countByStatus('completed');

        $rejectedOrders = $this->orderModel->countByStatus('rejected');

        // Ringkasan pendapatan (dari pesanan yang sudah completed)
        $totalRevenue = $this->orderModel->getTotalRevenue();

        // Ringkasan produk
        $totalActiveProducts = $this->productModel->countActiveProducts();

        $lowStockProducts = $this->productModel->countLowStockProducts();

        // Ringkasan pelanggan
        $totalCustomers = $this->userModel->countCustomers();

        // Pesanan terbaru
        $recentOrders = $this->orderModel->getRecentOrders(5);

        require_once "../app/views/admin/dashboard.php";
    }
}