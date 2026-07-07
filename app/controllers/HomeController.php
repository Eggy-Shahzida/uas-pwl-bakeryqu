<?php

class HomeController
{
    public function index()
    {
        $_SESSION['success'] = "Flash message berhasil dibuat.";
        require_once "../app/views/home/index.php";
    }
}