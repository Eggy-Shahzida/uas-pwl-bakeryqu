<?php

require_once "../app/models/OrderModel.php";

class AdminOrderController
{
    private $orderModel;

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

        $status = $_POST['status'] ?? '';

        $allowedStatus = ['pending', 'processing', 'shipping', 'completed', 'rejected'];

        if (!in_array($status, $allowedStatus, true)) {
            $_SESSION['error'] = "Status tidak valid.";
            header("Location: " . BASE_URL . "/admin/orders/{$id}");
            exit;
        }

        $this->orderModel->updateStatus($id, $status);

        $_SESSION['success'] = "Status pesanan berhasil diperbarui.";

        header("Location: " . BASE_URL . "/admin/orders/{$id}");

        exit;
    }
}