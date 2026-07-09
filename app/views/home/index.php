<?php

$pageScript = "home.js";

require_once "../app/views/layouts/header.php";

?>

<div class="hero-bakery">

    <h1>Roti &amp; Kue Segar Setiap Hari</h1>

    <p>
        Dibuat dari bahan pilihan, dipanggang setiap pagi dengan resep rumahan.
        Temukan roti favorit Anda di BakeryQu.
    </p>

    <a href="<?= BASE_URL ?>/products" class="btn btn-light-bakery">
        <i class="bi bi-basket3-fill"></i>
        Lihat Semua Produk
    </a>

</div>

<div class="row text-center g-4 mb-4">

    <div class="col-md-4">
        <i class="bi bi-egg-fried" style="font-size:2.2rem; color:var(--bakery-terracotta);"></i>
        <h5 class="mt-2">Bahan Berkualitas</h5>
        <p class="text-muted small">Dipilih langsung dari supplier terpercaya.</p>
    </div>

    <div class="col-md-4">
        <i class="bi bi-fire" style="font-size:2.2rem; color:var(--bakery-terracotta);"></i>
        <h5 class="mt-2">Dipanggang Segar</h5>
        <p class="text-muted small">Selalu fresh setiap hari, tanpa pengawet.</p>
    </div>

    <div class="col-md-4">
        <i class="bi bi-truck" style="font-size:2.2rem; color:var(--bakery-terracotta);"></i>
        <h5 class="mt-2">Pengiriman Cepat</h5>
        <p class="text-muted small">Sampai ke rumah Anda dalam kondisi terbaik.</p>
    </div>

</div>

<?php

require_once "../app/views/layouts/footer.php";

?>