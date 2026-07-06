<nav class="navbar navbar-expand-lg bg-white shadow-sm">

<div class="container">

<!-- Left: Logo -->
<a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">BakeryQu</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarNav">

<!-- Middle: Menu -->
<ul class="navbar-nav navbar-menu-center mx-auto">

<li class="nav-item">

<a class="nav-link" href="<?= BASE_URL ?>">Home</a>

</li>

<li class="nav-item">

<a class="nav-link" href="<?= BASE_URL ?>/products">Produk</a>

</li>

<!-- Tambahkan menu lain di sini jika diperlukan -->

</ul>

<!-- Right: Auth buttons -->
<ul class="navbar-nav navbar-auth-right ms-lg-auto gap-2">

<li class="nav-item">

<a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a>

</li>

<li class="nav-item">

<a class="btn btn-outline-dark btn-sm ms-1" href="<?= BASE_URL ?>/auth/register">Register</a>

</li>

</ul>

</div>

</div>

</nav>
