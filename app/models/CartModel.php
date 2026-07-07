<?php

require_once "../config/database.php";

class CartModel
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
    // mengambil cart berdasarkan user
    //------------------------------------------------
    public function getCartByUser($userId)
    {
        $sql = "SELECT *
                FROM carts
                WHERE user_id = :user_id
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':user_id', $userId);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // membuat cart baru
    //------------------------------------------------
    public function createCart($userId)
    {
        $sql = "INSERT INTO carts (user_id)
                VALUES (:user_id)";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':user_id', $userId);

        $statement->execute();

        return $this->conn->lastInsertId();
    }

    //------------------------------------------------
    // mengambil cart berdasarkan id
    //------------------------------------------------
    public function getCartById($cartId)
    {
        $sql = "SELECT *
                FROM carts
                WHERE id = :cart_id
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':cart_id', $cartId);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // mengecek apakah produk sudah ada di keranjang
    //------------------------------------------------
    public function findCartItem($cartId, $productId)
    {
        $sql = "SELECT *
                FROM cart_items
                WHERE cart_id = :cart_id
                AND product_id = :product_id
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':cart_id', $cartId);
        $statement->bindValue(':product_id', $productId);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // menambahkan produk ke cart_items
    //------------------------------------------------
    public function addCartItem($data)
    {
        $sql = "INSERT INTO cart_items
                (
                    cart_id,
                    product_id,
                    quantity,
                    price,
                    subtotal
                )
                VALUES
                (
                    :cart_id,
                    :product_id,
                    :quantity,
                    :price,
                    :subtotal
                )";

        $statement = $this->conn->prepare($sql);

        return $statement->execute($data);
    }

    //------------------------------------------------
    // memperbarui jumlah produk pada keranjang
    //------------------------------------------------
    public function updateQuantity($cartItemId, $quantity, $subtotal)
    {
        $sql = "UPDATE cart_items
                SET
                    quantity = :quantity,
                    subtotal = :subtotal
                WHERE id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':quantity', $quantity);
        $statement->bindValue(':subtotal', $subtotal);
        $statement->bindValue(':id', $cartItemId);

        return $statement->execute();
    }

    //------------------------------------------------
    // mengambil seluruh isi keranjang
    //------------------------------------------------
    public function getCartItems($cartId)
    {
        $sql = "SELECT
                    ci.*,
                    p.name,
                    p.image,
                    p.stock,
                    p.weight,
                    c.name AS category_name
                FROM cart_items ci
                INNER JOIN products p
                    ON ci.product_id = p.id
                INNER JOIN categories c
                    ON p.category_id = c.id
                WHERE ci.cart_id = :cart_id
                ORDER BY ci.id DESC";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':cart_id', $cartId);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // menghapus produk dari keranjang
    //------------------------------------------------
    public function deleteCartItem($cartItemId)
    {
        $sql = "DELETE FROM cart_items
                WHERE id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $cartItemId);

        return $statement->execute();
    }

    //------------------------------------------------
    // menghitung jumlah item pada keranjang
    //------------------------------------------------
    public function countCartItems($cartId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM cart_items
                WHERE cart_id = :cart_id";
        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':cart_id', $cartId);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // mengambil satu item keranjang berdasarkan id
    //------------------------------------------------
    public function getCartItemById($cartItemId)
    {
        $sql = "SELECT *
                FROM cart_items
                WHERE id = :id
                LIMIT 1";
        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':id', $cartItemId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}