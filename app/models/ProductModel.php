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
    // mengambil semua data produk dari database (untuk halaman publik)
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
    // mengambil satu produk berdasarkan id (untuk halaman publik)
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
    // mengambil satu produk berdasarkan slug (untuk halaman publik)
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

    //------------------------------------------------
    // menambah kembali stok produk (mis. saat pesanan ditolak/dibatalkan)
    //------------------------------------------------
    public function increaseStock($productId, $quantity)
    {
        $sql = "UPDATE products
                SET stock = stock + :quantity
                WHERE id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':quantity', $quantity, PDO::PARAM_INT);

        $statement->bindValue(':id', $productId, PDO::PARAM_INT);

        return $statement->execute();
    }

    // =====================================================
    // ADMIN — CRUD PRODUK
    // =====================================================

    //------------------------------------------------
    // mengambil semua produk untuk admin (termasuk yang inactive,
    // tapi TIDAK termasuk yang sudah soft-delete), dengan pencarian
    //------------------------------------------------
    public function getAllProductsAdmin($search = '', $category = 0)
    {
        $sql = "SELECT
                    p.*,
                    c.name AS category_name
                FROM products p
                INNER JOIN categories c
                    ON p.category_id = c.id
                WHERE p.deleted_at IS NULL";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND p.name LIKE :search";
            $params[':search'] = "%{$search}%";
        }

        if ($category > 0) {
            $sql .= " AND p.category_id = :category";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $statement = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }

        $statement->execute();

        return $statement->fetchAll();
    }

    //------------------------------------------------
    // mengambil satu produk untuk admin (tanpa filter status,
    // dipakai di form edit — status inactive tetap harus bisa diedit)
    //------------------------------------------------
    public function getProductByIdForAdmin($id)
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
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //------------------------------------------------
    // membuat produk baru
    //------------------------------------------------
    public function createProduct($data)
    {
        $sql = "INSERT INTO products (
                    category_id, name, slug, description,
                    price, weight, stock, image, status
                ) VALUES (
                    :category_id, :name, :slug, :description,
                    :price, :weight, :stock, :image, :status
                )";

        $statement = $this->conn->prepare($sql);

        $statement->execute([
            ':category_id' => $data['category_id'],
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':weight' => $data['weight'],
            ':stock' => $data['stock'],
            ':image' => $data['image'],
            ':status' => $data['status'],
        ]);

        return (int) $this->conn->lastInsertId();
    }

    //------------------------------------------------
    // memperbarui produk. Jika $data['image'] null, gambar lama dipertahankan.
    //------------------------------------------------
    public function updateProduct($id, $data)
    {
        $sql = "UPDATE products SET
                    category_id = :category_id,
                    name = :name,
                    slug = :slug,
                    description = :description,
                    price = :price,
                    weight = :weight,
                    stock = :stock,
                    status = :status";

        if ($data['image'] !== null) {
            $sql .= ", image = :image";
        }

        $sql .= " WHERE id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':category_id', $data['category_id']);
        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':slug', $data['slug']);
        $statement->bindValue(':description', $data['description']);
        $statement->bindValue(':price', $data['price']);
        $statement->bindValue(':weight', $data['weight']);
        $statement->bindValue(':stock', $data['stock']);
        $statement->bindValue(':status', $data['status']);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        if ($data['image'] !== null) {
            $statement->bindValue(':image', $data['image']);
        }

        return $statement->execute();
    }

    //------------------------------------------------
    // soft delete produk
    //------------------------------------------------
    public function softDeleteProduct($id)
    {
        $sql = "UPDATE products
                SET deleted_at = NOW()
                WHERE id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    //------------------------------------------------
    // cek apakah slug sudah dipakai produk lain (untuk validasi unik)
    //------------------------------------------------
    public function isSlugExists($slug, $excludeId = null)
    {
        $sql = "SELECT id FROM products WHERE slug = :slug";

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }

        $sql .= " LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':slug', $slug);

        if ($excludeId) {
            $statement->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        }

        $statement->execute();

        return (bool) $statement->fetch();
    }

    //------------------------------------------------
    // menghitung jumlah produk aktif (untuk dashboard)
    //------------------------------------------------
    public function countActiveProducts()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM products
                WHERE deleted_at IS NULL
                    AND status = 'active'";

        $statement = $this->conn->prepare($sql);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    //------------------------------------------------
    // menghitung jumlah produk dengan stok menipis (<= 5)
    //------------------------------------------------
    public function countLowStockProducts($threshold = 5)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM products
                WHERE deleted_at IS NULL
                    AND status = 'active'
                    AND stock <= :threshold";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':threshold', $threshold, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }
}