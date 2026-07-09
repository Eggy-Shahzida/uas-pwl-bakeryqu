<?php

require_once "../app/models/ProductModel.php";
require_once "../app/models/CategoryModel.php";

class AdminProductController
{
    private $productModel;

    private $categoryModel;

    private $uploadDir;

    private $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

    private $maxFileSize = 2097152; // 2MB

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

        $this->productModel = new ProductModel();

        $this->categoryModel = new CategoryModel();

        $this->uploadDir = "../public/assets/uploads/products/";
    }

    //------------------------------------------------
    // menampilkan daftar produk
    //------------------------------------------------
    public function index()
    {
        $search = trim($_GET['search'] ?? '');

        $category = (int) ($_GET['category'] ?? 0);

        $products = $this->productModel->getAllProductsAdmin($search, $category);

        $categories = $this->categoryModel->getAllCategories();

        require_once "../app/views/admin_product/index.php";
    }

    //------------------------------------------------
    // menampilkan form tambah produk
    //------------------------------------------------
    public function create()
    {
        $categories = $this->categoryModel->getAllCategories();

        $old = $_SESSION['old'] ?? [];

        unset($_SESSION['old']);

        require_once "../app/views/admin_product/create.php";
    }

    //------------------------------------------------
    // menyimpan produk baru
    //------------------------------------------------
    public function store()
    {
        $data = $this->validateAndPrepare();

        if ($data === null) {
            header("Location: " . BASE_URL . "/admin/products/create");
            exit;
        }

        // Upload gambar (wajib saat tambah produk baru)
        $uploadResult = $this->handleUpload();

        if ($uploadResult['error']) {
            $_SESSION['error'] = $uploadResult['error'];
            $_SESSION['old'] = $_POST;
            header("Location: " . BASE_URL . "/admin/products/create");
            exit;
        }

        $data['image'] = $uploadResult['filename'];

        $this->productModel->createProduct($data);

        $_SESSION['success'] = "Produk berhasil ditambahkan.";

        header("Location: " . BASE_URL . "/admin/products");

        exit;
    }

    //------------------------------------------------
    // menampilkan form edit produk
    //------------------------------------------------
    public function edit($id)
    {
        $product = $this->productModel->getProductByIdForAdmin((int) $id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan.";
            header("Location: " . BASE_URL . "/admin/products");
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();

        $old = $_SESSION['old'] ?? [];

        unset($_SESSION['old']);

        require_once "../app/views/admin_product/edit.php";
    }

    //------------------------------------------------
    // memperbarui produk
    //------------------------------------------------
    public function update($id)
    {
        $id = (int) $id;

        $product = $this->productModel->getProductByIdForAdmin($id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan.";
            header("Location: " . BASE_URL . "/admin/products");
            exit;
        }

        $data = $this->validateAndPrepare($id);

        if ($data === null) {
            header("Location: " . BASE_URL . "/admin/products/edit/{$id}");
            exit;
        }

        // Upload gambar baru hanya jika user memilih file baru
        $data['image'] = null;

        if (!empty($_FILES['image']['name'])) {

            $uploadResult = $this->handleUpload();

            if ($uploadResult['error']) {
                $_SESSION['error'] = $uploadResult['error'];
                $_SESSION['old'] = $_POST;
                header("Location: " . BASE_URL . "/admin/products/edit/{$id}");
                exit;
            }

            $data['image'] = $uploadResult['filename'];

            // hapus gambar lama supaya tidak menumpuk file sampah
            if (!empty($product['image'])) {
                $this->deleteImageFile($product['image']);
            }
        }

        $this->productModel->updateProduct($id, $data);

        $_SESSION['success'] = "Produk berhasil diperbarui.";

        header("Location: " . BASE_URL . "/admin/products");

        exit;
    }

    //------------------------------------------------
    // menghapus produk (soft delete)
    //------------------------------------------------
    public function delete($id)
    {
        $id = (int) $id;

        $product = $this->productModel->getProductByIdForAdmin($id);

        if (!$product) {
            $_SESSION['error'] = "Produk tidak ditemukan.";
            header("Location: " . BASE_URL . "/admin/products");
            exit;
        }

        $this->productModel->softDeleteProduct($id);

        $_SESSION['success'] = "Produk berhasil dihapus.";

        header("Location: " . BASE_URL . "/admin/products");

        exit;
    }

    //------------------------------------------------
    // helper: validasi input form produk (dipakai store() & update())
    // mengembalikan array data siap simpan, atau null jika gagal validasi
    //------------------------------------------------
    private function validateAndPrepare($excludeId = null)
    {
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $weight = (int) ($_POST['weight'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        $errors = [];

        if ($name === '' || strlen($name) < 3) {
            $errors[] = "Nama produk minimal 3 karakter.";
        }

        if ($categoryId <= 0) {
            $errors[] = "Kategori wajib dipilih.";
        }

        if ($price <= 0) {
            $errors[] = "Harga harus lebih dari 0.";
        }

        if ($weight <= 0) {
            $errors[] = "Berat produk harus lebih dari 0 gram.";
        }

        if ($stock < 0) {
            $errors[] = "Stok tidak boleh negatif.";
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            $status = 'active';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(' ', $errors);
            $_SESSION['old'] = $_POST;
            return null;
        }

        // Generate slug unik dari nama produk
        $baseSlug = $this->generateSlug($name);

        $slug = $baseSlug;

        $suffix = 1;

        while ($this->productModel->isSlugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return [
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'weight' => $weight,
            'stock' => $stock,
            'status' => $status,
        ];
    }

    //------------------------------------------------
    // helper: ubah nama produk menjadi slug URL-friendly
    //------------------------------------------------
    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));

        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        return trim($slug, '-');
    }

    //------------------------------------------------
    // helper: proses upload file gambar produk
    //------------------------------------------------
    private function handleUpload()
    {
        if (empty($_FILES['image']['name'])) {
            return ['error' => 'Gambar produk wajib diunggah.', 'filename' => null];
        }

        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Gagal mengunggah gambar.', 'filename' => null];
        }

        if ($file['size'] > $this->maxFileSize) {
            return ['error' => 'Ukuran gambar maksimal 2MB.', 'filename' => null];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowedExt, true)) {
            return ['error' => 'Format gambar harus jpg, jpeg, png, atau webp.', 'filename' => null];
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $filename = 'product-' . uniqid() . '.' . $ext;

        $destination = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['error' => 'Gagal menyimpan file gambar ke server.', 'filename' => null];
        }

        return ['error' => null, 'filename' => $filename];
    }

    //------------------------------------------------
    // helper: hapus file gambar lama dari server
    //------------------------------------------------
    private function deleteImageFile($filename)
    {
        $path = $this->uploadDir . $filename;

        if (is_file($path)) {
            @unlink($path);
        }
    }
}