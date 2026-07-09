<?php

require_once "../app/models/OrderModel.php";

class OrderController
{
    private $orderModel;

    //------------------------------------------------
    // membuat objek OrderModel
    //------------------------------------------------
    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    //------------------------------------------------
    // menampilkan daftar pesanan milik user ("Pesanan Saya")
    //------------------------------------------------
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $orders = $this->orderModel->getOrdersByUser($userId);

        require_once "../app/views/order/index.php";
    }

    //------------------------------------------------
    // menampilkan detail satu pesanan
    //------------------------------------------------
    public function detail($orderId)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu.";
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $order = $this->orderModel->getOrderById((int) $orderId, $userId);

        if (!$order) {
            $_SESSION['error'] = "Pesanan tidak ditemukan.";
            header("Location: " . BASE_URL . "/order");
            exit;
        }

        $items = $this->orderModel->getOrderItems($order['id']);

        require_once "../app/views/order/detail.php";
    }
}