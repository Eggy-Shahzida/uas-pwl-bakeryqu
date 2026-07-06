<?php

require_once "../app/models/ProductModel.php";
require_once "../app/models/CategoryModel.php";

class ProductController
{
    private $productModel;
    private $categoryModel;


    //------------------------------------------------
    // membuat objek ProductModel
    //------------------------------------------------
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    //------------------------------------------------
    // mengambil semua data produk dari database
    // menampilkan halaman index produk
    //------------------------------------------------
    public function index()
    {
        // Ambil keyword pencarian
        $search = isset($_GET['search'])
            ? trim($_GET['search'])
            : '';

        // Ambil kategori
        $category = isset($_GET['category'])
            ? (int) $_GET['category']
            : 0;

        // Mengambil data kategori
        $categories = $this->categoryModel->getAllCategories();

        // ambil data produk
        $products = $this->productModel->getAllProducts($search, $category);

        // Menampilkan halaman
        require_once "../app/views/product/index.php";
    }
}