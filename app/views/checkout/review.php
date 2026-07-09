<?php

require_once "../app/views/layouts/header.php";

?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">Review Pesanan</h2>
        <p class="text-muted">Periksa kembali data sebelum melanjutkan ke konfirmasi pesanan.</p>
    </div>
</div>

<div class="row">

    <div class="col-lg-7">

        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Penerima</h5>
                <a href="<?= BASE_URL ?>/checkout" class="btn btn-sm btn-outline-secondary">
                    Ubah
                </a>
            </div>

            <div class="card-body">

                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 160px;">Nama Penerima</td>
                        <td>: <?= htmlspecialchars($checkoutData['receiver_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nomor Telepon</td>
                        <td>: <?= htmlspecialchars($checkoutData['phone']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Provinsi</td>
                        <td>: <?= htmlspecialchars($checkoutData['province_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kota / Kabupaten</td>
                        <td>: <?= htmlspecialchars($checkoutData['city_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kecamatan</td>
                        <td>: <?= htmlspecialchars($checkoutData['district_name']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted align-top">Alamat Lengkap</td>
                        <td>: <?= nl2br(htmlspecialchars($checkoutData['address'])) ?></td>
                    </tr>
                    <?php if (!empty($checkoutData['note'])): ?>
                    <tr>
                        <td class="text-muted align-top">Catatan</td>
                        <td>: <?= nl2br(htmlspecialchars($checkoutData['note'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Kurir</td>
                        <td>
                            : <?= strtoupper(htmlspecialchars($checkoutData['courier'])) ?>
                            <?php if (!empty($checkoutData['shipping_service'])): ?>
                                - <?= htmlspecialchars($checkoutData['shipping_service']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Produk yang Dipesan</h5>
            </div>

            <div class="card-body">

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Ringkasan Pembayaran</h5>
            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>Rp <?= number_format($subtotal, 0, ',', '.') ?></strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Ongkir (<?= strtoupper(htmlspecialchars($checkoutData['courier'])) ?>)</span>
                    <strong>Rp <?= number_format($checkoutData['shipping_cost'], 0, ',', '.') ?></strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <h5>Total</h5>
                    <h5 class="text-primary">Rp <?= number_format($grandTotal, 0, ',', '.') ?></h5>
                </div>

                <form action="<?= BASE_URL ?>/checkout/confirm" method="POST">
                    <button type="submit" class="btn btn-success w-100">
                        Konfirmasi Pesanan
                    </button>
                </form>

                <a href="<?= BASE_URL ?>/checkout" class="btn btn-outline-secondary w-100 mt-2">
                    Kembali / Ubah Data
                </a>

            </div>

        </div>

    </div>

</div>

<?php

require_once "../app/views/layouts/footer.php";

?>