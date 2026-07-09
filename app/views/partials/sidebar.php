<?php

// Menentukan menu mana yang sedang aktif berdasarkan URL saat ini
$currentUrl = trim($_GET['url'] ?? '', '/');

function isActiveMenu($prefix, $currentUrl)
{
    return strpos($currentUrl, $prefix) === 0 ? 'active' : '';
}

?>

<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white admin-sidebar" style="width: 240px; min-height: 100vh;">

    <a href="<?= BASE_URL ?>/admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-5 fw-bold">BakeryQu Admin</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href="<?= BASE_URL ?>/admin" class="nav-link text-white <?= isActiveMenu('admin', $currentUrl) === 'active' && $currentUrl === 'admin' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>/admin/products" class="nav-link text-white <?= isActiveMenu('admin/products', $currentUrl) ?>">
                <i class="bi bi-box-seam me-2"></i>
                Kelola Produk
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>/admin/orders" class="nav-link text-white <?= isActiveMenu('admin/orders', $currentUrl) ?>">
                <i class="bi bi-receipt me-2"></i>
                Kelola Pesanan
            </a>
        </li>

    </ul>

    <hr>

    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li>
                <a class="dropdown-item" href="<?= BASE_URL ?>">
                    Lihat Situs
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?= BASE_URL ?>/logout">
                    Logout
                </a>
            </li>
        </ul>
    </div>

</div>

<style>
.admin-sidebar .nav-link.active {
    background-color: #0d6efd;
}
.admin-sidebar .nav-link {
    opacity: 0.85;
}
.admin-sidebar .nav-link:hover {
    opacity: 1;
}
</style>