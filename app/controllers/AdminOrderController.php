<?php

require_once "../app/models/OrderModel.php";
require_once "../app/models/ProductModel.php";

class AdminOrderController
{
    private $orderModel;

    private $productModel;

    //------------------------------------------------
    // membuat objek model & pastikan yang akses adalah admin
    //------------------------------------------------
    public function __construct()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $this->orderModel = new OrderModel();

        $this->productModel = new ProductModel();
    }

    //------------------------------------------------
    // menampilkan daftar seluruh pesanan (opsional filter status)
    //------------------------------------------------
    public function index()
    {
        $status = trim($_GET['status'] ?? '');

        $orders = $this->orderModel->getAllOrders($status);

        require_once "../app/views/admin_order/index.php";
    }

    //------------------------------------------------
    // menampilkan detail satu pesanan
    //------------------------------------------------
    public function detail($id)
    {
        $order = $this->orderModel->getOrderByIdForAdmin((int) $id);

        if (!$order) {
            $_SESSION['error'] = "Pesanan tidak ditemukan.";
            header("Location: " . BASE_URL . "/admin/orders");
            exit;
        }

        $items = $this->orderModel->getOrderItems($order['id']);

        require_once "../app/views/admin_order/detail.php";
    }

    //------------------------------------------------
    // memperbarui status pesanan
    // - jika status berubah MENJADI 'rejected' -> stok produk dikembalikan
    // - jika status berubah DARI 'rejected' ke status lain -> stok dikurangi lagi
    //   (supaya konsisten kalau admin salah klik / batal menolak)
    //------------------------------------------------
    public function updateStatus($id)
    {
        $id = (int) $id;

        $order = $this->orderModel->getOrderByIdForAdmin($id);

        if (!$order) {
            $_SESSION['error'] = "Pesanan tidak ditemukan.";
            header("Location: " . BASE_URL . "/admin/orders");
            exit;
        }

        $newStatus = $_POST['status'] ?? '';

        $allowedStatus = ['pending', 'processing', 'shipping', 'completed', 'rejected'];

        if (!in_array($newStatus, $allowedStatus, true)) {
            $_SESSION['error'] = "Status tidak valid.";
            header("Location: " . BASE_URL . "/admin/orders/{$id}");
            exit;
        }

        $oldStatus = $order['status'];

        // Status tidak berubah, tidak perlu proses apa-apa
        if ($oldStatus === $newStatus) {
            $_SESSION['success'] = "Status pesanan berhasil diperbarui.";
            header("Location: " . BASE_URL . "/admin/orders/{$id}");
            exit;
        }

        $items = $this->orderModel->getOrderItems($id);

        // Transisi -> 'rejected': kembalikan stok produk
        if ($newStatus === 'rejected' && $oldStatus !== 'rejected') {

            foreach ($items as $item) {
                if ($item['product_id']) {
                    $this->productModel->increaseStock($item['product_id'], $item['quantity']);
                }
            }

        }

        // Transisi dari 'rejected' -> status lain: kurangi stok lagi
        if ($oldStatus === 'rejected' && $newStatus !== 'rejected') {

            foreach ($items as $item) {
                if ($item['product_id']) {
                    $this->productModel->decreaseStock($item['product_id'], $item['quantity']);
                }
            }

        }

        $this->orderModel->updateStatus($id, $newStatus);

        $_SESSION['success'] = "Status pesanan berhasil diperbarui.";

        header("Location: " . BASE_URL . "/admin/orders/{$id}");

        exit;
    }
}