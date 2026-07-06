<?php

require_once "../app/models/UserModel.php";

class AuthController
{
    private UserModel $userModel;

    //------------------------------------------------
    // Constructor
    //------------------------------------------------
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    //------------------------------------------------
    // Menampilkan halaman login
    //------------------------------------------------
    public function showLogin()
    {
        require_once "../app/views/auth/login.php";
    }

    //------------------------------------------------
    // Proses login
    //------------------------------------------------
    public function login()
    {
        // Ambil data dari form
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validasi input
        if (empty($email) || empty($password)) {
            exit('Email dan password wajib diisi.');
        }
        
        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit('Format email tidak valid.');
        }
        
        // Cari user berdasarkan email
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            exit('Email atau password salah.');
        }
        
        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            exit('Email atau password salah.');
        }
        
        // Simpan session login
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        
        // Redirect berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }
        header("Location: " . BASE_URL);
        exit;
    }

    //------------------------------------------------
    // Menampilkan halaman register
    //------------------------------------------------
    public function showRegister()
    {
        require_once "../app/views/auth/register.php";
    }

    //------------------------------------------------
    // Proses register
    //------------------------------------------------
    public function register()
    {
        // Ambil data dari form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validasi input
        if (
            empty($name) ||
            empty($email) ||
            empty($password) ||
            empty($confirmPassword)
        ) {
            exit('Semua field wajib diisi.');
        }

        // Validasi nama
        if (!preg_match('/^[\p{L}\s]+$/u', $name)) {
            exit('Nama hanya boleh berisi huruf dan spasi.');
        }
        if (strlen($name) < 3 || strlen($name) > 100) {
            exit('Nama harus terdiri dari 3 sampai 100 karakter.');
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit('Format email tidak valid.');
        }

        // Password minimal
        if (strlen($password) < 8) {
            exit('Password minimal 8 karakter.');
        }

        // Konfirmasi password
        if ($password !== $confirmPassword) {
            exit('Konfirmasi password tidak sesuai.');
        }

        // Cek email sudah digunakan
        $user = $this->userModel->findByEmail($email);
        if ($user) {
            exit('Email sudah terdaftar.');
        }

        // Simpan user
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        ];
        $success = $this->userModel->create($data);
        // Redirect
        if ($success) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        exit('Register gagal.');
    }


    //------------------------------------------------
    // Logout
    //------------------------------------------------
    public function logout()
    {
        // Hapus seluruh data session
        $_SESSION = [];
        
        // Hapus session
        session_destroy();
        
        // Redirect ke Home
        header("Location: " . BASE_URL);
        exit;
    }
}