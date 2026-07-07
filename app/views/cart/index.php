<?php

$pageScript = "cart.js";
require_once "../app/views/layouts/header.php";

?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">Keranjang Belanja</h2>
        <p class="text-muted">Daftar produk yang akan Anda checkout.</p>
    </div>
</div>

<?php if (empty($items)) : ?>

<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <h4>Keranjang masih kosong</h4>
        <p class="text-muted">Silakan pilih produk terlebih dahulu.</p>

        <a href="<?= BASE_URL ?>/products" class="btn btn-primary">
            Belanja Sekarang
        </a>
    </div>
</div>

<?php else : ?>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th width="140">Harga</th>
                    <th width="170" class="text-center">Jumlah</th>
                    <th width="170">Subtotal</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php $grandTotal = 0; ?>

            <?php foreach ($items as $item) : ?>

            <?php
                $grandTotal += $item['subtotal'];

                $image = !empty($item['image'])
                    ? $item['image']
                    : 'no-image.png';
            ?>

                <tr>

                    <td>

                        <div class="d-flex align-items-center">

                            <img
                                src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($image) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                width="80"
                                class="rounded me-3">

                            <div>

                                <h6 class="mb-1">

                                    <?= htmlspecialchars($item['name']) ?>

                                </h6>

                                <?php if (!empty($item['category_name'])) : ?>

                                    <small class="text-muted">

                                        <?= htmlspecialchars($item['category_name']) ?>

                                    </small>

                                <?php endif; ?>

                            </div>

                        </div>

                    </td>

                    <td>

                        Rp <?= number_format($item['price'], 0, ',', '.') ?>

                    </td>

                    <td class="text-center">

                        <div class="d-inline-flex align-items-center">

                            <form action="<?= BASE_URL ?>/cart/update" method="POST" class="m-0">

                                <input
                                    type="hidden"
                                    name="cart_item_id"
                                    value="<?= $item['id'] ?>">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="minus">

                                <button
                                    type="submit"
                                    class="btn btn-outline-secondary btn-sm">

                                    <i class="bi bi-dash"></i>

                                </button>

                            </form>

                            <span class="px-3 fw-bold">

                                <?= $item['quantity'] ?>

                            </span>

                            <form action="<?= BASE_URL ?>/cart/update" method="POST" class="m-0">

                                <input
                                    type="hidden"
                                    name="cart_item_id"
                                    value="<?= $item['id'] ?>">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="plus">

                                <button
                                    type="submit"
                                    class="btn btn-outline-secondary btn-sm">

                                    <i class="bi bi-plus"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                    <td>

                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>

                    </td>

                    <td class="text-center">

                        <form
                            action="<?= BASE_URL ?>/cart/remove"
                            method="POST"
                            class="m-0"
                            onsubmit="return confirm('Hapus produk dari keranjang?');">

                            <input
                                type="hidden"
                                name="cart_item_id"
                                value="<?= $item['id'] ?>">

                            <button
                                type="submit"
                                class="btn btn-outline-danger btn-sm">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>
</div>

<div class="card shadow-sm mt-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Total</h4>

            <h4 class="text-primary mb-0">

                Rp <?= number_format($grandTotal, 0, ',', '.') ?>

            </h4>

        </div>

        <div class="text-end mt-4">

            <a href="#" class="btn btn-success">

                Checkout

            </a>

        </div>

    </div>

</div>

<?php endif; ?>

<?php require_once "../app/views/layouts/footer.php"; ?>