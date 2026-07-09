<?php

require_once "../config/database.php";

class ProductModel
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
    // mengambil semua data produk dari database
    //------------------------------------------------
    public function getAllProducts($search = '', $category = 0)
    {
        $sql = "SELECT
                    p.*,
                    c.name AS category_name
                FROM products p
                INNER JOIN categories c
                    ON p.category_id = c.id
                WHERE
                    p.deleted_at IS NULL
                    AND p.status = 'active'";
        $params = [];
        // Filter pencarian
        if (!empty($search)) {
            $sql .= " AND p.name LIKE :search";
            $params[':search'] = "%{$search}%";
        }
        // Filter kategori
        if ($category > 0) {
            $sql .= " AND p.category_id = :category";
            $params[':category'] = $category;
        }
        // Urutkan produk terbaru
        $sql .= " ORDER BY p.created_at DESC";
        $statement = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        return $statement->fetchAll();
    }

    //------------------------------------------------
    // mengambil satu produk berdasarkan id
    //------------------------------------------------
    public function getProductById($id)
    {
        $sql = "SELECT
                    p.*,
                    c.name AS category_name
                FROM products p
                INNER JOIN categories c
                    ON p.category_id = c.id
                WHERE
                    p.id = :id
                    AND p.deleted_at IS NULL
                    AND p.status = 'active'
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // mengambil satu produk berdasarkan slug
    //------------------------------------------------
    public function getProductBySlug($slug)
    {
        $sql = "SELECT
                    p.*,
                    c.name AS category_name
                FROM products p
                INNER JOIN categories c
                    ON p.category_id = c.id
                WHERE
                    p.slug = :slug
                    AND p.status = 'active'
                    AND p.deleted_at IS NULL
                LIMIT 1";
        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':slug', $slug);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // mengurangi stok produk setelah pesanan dikonfirmasi
    //------------------------------------------------
    public function decreaseStock($productId, $quantity)
    {
        $sql = "UPDATE products
                SET stock = stock - :quantity
                WHERE id = :id
                    AND stock >= :quantity";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);

        $statement->bindValue(':id', $productId, PDO::PARAM_INT);

        return $statement->execute();
    }

}