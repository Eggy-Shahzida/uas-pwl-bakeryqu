<?php

$pageScript = "product.js";

require_once "../app/views/layouts/header.php";


?>

<div class="row mb-4">

    <div class="col-md-12">

        <h2 class="fw-bold">Daftar Produk</h2>

        <p class="text-muted">
            Temukan berbagai pilihan roti dan kue favorit Anda.
        </p>

    </div>

</div>

<!-- FILTER -->

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form action="<?= BASE_URL ?>/products" method="GET">

            <div class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label class="form-label">
                        Cari Produk
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Masukkan nama produk..."
                        value="<?= htmlspecialchars($search) ?>">

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        Kategori
                    </label>

                    <select
                        name="category"
                        class="form-select">

                        <option value="0">
                            Semua Kategori
                        </option>

                        <?php foreach ($categories as $item): ?>

                            <option
                                value="<?= $item['id'] ?>"
                                <?= ($category == $item['id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars($item['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-3 d-grid">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Cari Produk

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- DAFTAR PRODUK -->

<div class="row">

<?php if (!empty($products)): ?>

    <?php foreach ($products as $product): ?>

        <?php

        $description = strip_tags($product['description']);

        if (mb_strlen($description) > 80) {

            $description = mb_substr($description, 0, 80) . "...";

        }

        ?>

        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="card h-100 shadow-sm">

                <img
                    src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                    class="card-img-top product-image"
                    alt="<?= htmlspecialchars($product['name']) ?>">

                <div class="card-body d-flex flex-column">

                    <span class="badge bg-secondary mb-2 align-self-start">

                        <?= htmlspecialchars($product['category_name']) ?>

                    </span>

                    <h5 class="card-title">

                        <?= htmlspecialchars($product['name']) ?>

                    </h5>

                    <p class="card-text text-muted small">

                        <?= htmlspecialchars($description) ?>

                    </p>

                    <h5 class="fw-bold text-primary">

                        Rp <?= number_format($product['price'], 0, ',', '.') ?>

                    </h5>

                    <p class="mb-3">

                        Stok :
                        <strong><?= $product['stock'] ?></strong>

                    </p>

                    <a
                        href="<?= BASE_URL ?>/products/<?= urlencode($product['slug']) ?>"
                        class="btn btn-outline-primary mt-auto">

                        Lihat Detail

                    </a>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="col-md-12">

        <div class="alert alert-warning text-center">

            Produk yang Anda cari tidak ditemukan.

        </div>

    </div>

<?php endif; ?>

</div>

<?php

require_once "../app/views/layouts/footer.php";

?>