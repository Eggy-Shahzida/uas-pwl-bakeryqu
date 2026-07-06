<?php

require_once "../config/database.php";

class CategoryModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();

        $this->conn = $database->connect();
    }

    /**
     * Mengambil seluruh kategori yang masih aktif
     */
    public function getAllCategories()
    {
        $sql = "SELECT *
                FROM categories
                ORDER BY name ASC";

        $statement = $this->conn->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }
}