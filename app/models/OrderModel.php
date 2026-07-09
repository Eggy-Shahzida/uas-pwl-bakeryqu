<?php

require_once "../config/database.php";

class OrderModel
{
    private $conn;

    //------------------------------------------------
    // membuat koneksi ke database menggunakan PDO
    //------------------------------------------------
    public function __construct()
    {
        $database = new Database();

        $this->conn = $database->connect();
    }

    //------------------------------------------------
    // membuat kode pesanan unik, contoh: ORD-20260708-A1B2C3
    //------------------------------------------------
    public function generateOrderCode()
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    //------------------------------------------------
    // membuat order baru, mengembalikan id order yang baru dibuat
    //------------------------------------------------
    public function createOrder($data)
    {
        $sql = "INSERT INTO orders (
                    order_code, user_id, receiver_name, phone,
                    province_id, province_name, city_id, city_name,
                    district_id, district_name, address, note,
                    courier, service, shipping_cost, subtotal, total, status
                ) VALUES (
                    :order_code, :user_id, :receiver_name, :phone,
                    :province_id, :province_name, :city_id, :city_name,
                    :district_id, :district_name, :address, :note,
                    :courier, :service, :shipping_cost, :subtotal, :total, :status
                )";

        $statement = $this->conn->prepare($sql);

        $statement->execute([
            ':order_code' => $data['order_code'],
            ':user_id' => $data['user_id'],
            ':receiver_name' => $data['receiver_name'],
            ':phone' => $data['phone'],
            ':province_id' => $data['province_id'],
            ':province_name' => $data['province_name'],
            ':city_id' => $data['city_id'],
            ':city_name' => $data['city_name'],
            ':district_id' => $data['district_id'],
            ':district_name' => $data['district_name'],
            ':address' => $data['address'],
            ':note' => $data['note'] ?: null,
            ':courier' => $data['courier'],
            ':service' => $data['service'],
            ':shipping_cost' => $data['shipping_cost'],
            ':subtotal' => $data['subtotal'],
            ':total' => $data['total'],
            ':status' => $data['status'] ?? 'pending',
        ]);

        return (int) $this->conn->lastInsertId();
    }

    //------------------------------------------------
    // menambahkan satu item ke order_items
    //------------------------------------------------
    public function addOrderItem($data)
    {
        $sql = "INSERT INTO order_items (
                    order_id, product_id, product_name, product_image,
                    product_weight, price, quantity, subtotal
                ) VALUES (
                    :order_id, :product_id, :product_name, :product_image,
                    :product_weight, :price, :quantity, :subtotal
                )";

        $statement = $this->conn->prepare($sql);

        $statement->execute([
            ':order_id' => $data['order_id'],
            ':product_id' => $data['product_id'],
            ':product_name' => $data['product_name'],
            ':product_image' => $data['product_image'],
            ':product_weight' => $data['product_weight'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':subtotal' => $data['subtotal'],
        ]);
    }

    //------------------------------------------------
    // mengambil semua pesanan milik user (untuk halaman "Pesanan Saya")
    //------------------------------------------------
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT *
                FROM orders
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }

    //------------------------------------------------
    // mengambil satu pesanan milik user berdasarkan id
    // (validasi user_id supaya user tidak bisa lihat order orang lain)
    //------------------------------------------------
    public function getOrderById($orderId, $userId)
    {
        $sql = "SELECT *
                FROM orders
                WHERE id = :id
                    AND user_id = :user_id
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $orderId, PDO::PARAM_INT);

        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // mengambil semua item dari sebuah pesanan
    //------------------------------------------------
    public function getOrderItems($orderId)
    {
        $sql = "SELECT *
                FROM order_items
                WHERE order_id = :order_id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':order_id', $orderId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }
}