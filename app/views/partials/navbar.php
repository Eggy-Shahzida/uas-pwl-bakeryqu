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
<li class="nav-item">

<a href="<?= BASE_URL ?>/cart" class="nav-link">
    <i class="bi bi-cart3"></i>
    Keranjang
</a>

</li>

<!-- Tambahkan menu lain di sini jika diperlukan -->

</ul>

<!-- Right: Auth buttons -->
<ul class="navbar-nav ms-auto">

    <?php if (isset($_SESSION['user'])) : ?>

        <li class="nav-item dropdown">

            <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown">

                Halo,
                <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong>

            </a>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>

                    <a
                        class="dropdown-item"
                        href="#">

                        Profil

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="#">

                        Pesanan Saya

                    </a>

                </li>

                <li>

                    <hr class="dropdown-divider">

                </li>

                <li>

                    <a
                        class="dropdown-item text-danger"
                        href="<?= BASE_URL ?>/logout">

                        Logout

                    </a>

                </li>

            </ul>

        </li>

    <?php else : ?>

        <li class="nav-item">

            <a
                class="nav-link"
                href="<?= BASE_URL ?>/login">

                Login

            </a>

        </li>

        <li class="nav-item">

            <a
                class="btn btn-primary ms-2"
                href="<?= BASE_URL ?>/register">

                Register

            </a>

        </li>

    <?php endif; ?>

</ul>

</div>

</div>

</nav>
