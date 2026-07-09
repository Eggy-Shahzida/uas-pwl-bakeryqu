<?php

require_once "../app/views/layouts/admin_header.php";

$statusMap = [
    'pending' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-warning text-dark'],
    'processing' => ['label' => 'Diproses', 'class' => 'bg-info text-dark'],
    'shipping' => ['label' => 'Dikirim', 'class' => 'bg-primary'],
    'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
];

$status = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary'];

?>

<a href="<?= BASE_URL ?>/admin/orders" class="text-decoration-none">&larr; Kembali ke Kelola Pesanan</a>

        <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
            <h2 class="fw-bold mb-0">
                Pesanan <?= htmlspecialchars($order['order_code']) ?>
                <span class="badge <?= $status['class'] ?> align-middle"><?= $status['label'] ?></span>
            </h2>
        </div>

        <div class="row">

            <div class="col-lg-7">

                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Data Penerima</h5>
                    </div>

                    <div class="card-body">

                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 160px;">Pembeli (Akun)</td>
                                <td>: <?= htmlspecialchars($order['buyer_name']) ?> (<?= htmlspecialchars($order['buyer_email']) ?>)</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Penerima</td>
                                <td>: <?= htmlspecialchars($order['receiver_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nomor Telepon</td>
                                <td>: <?= htmlspecialchars($order['phone']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Provinsi</td>
                                <td>: <?= htmlspecialchars($order['province_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kota / Kabupaten</td>
                                <td>: <?= htmlspecialchars($order['city_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kecamatan</td>
                                <td>: <?= htmlspecialchars($order['district_name']) ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted align-top">Alamat Lengkap</td>
                                <td>: <?= nl2br(htmlspecialchars($order['address'])) ?></td>
                            </tr>
                            <?php if (!empty($order['note'])): ?>
                            <tr>
                                <td class="text-muted align-top">Catatan</td>
                                <td>: <?= nl2br(htmlspecialchars($order['note'])) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="text-muted">Kurir</td>
                                <td>
                                    : <?= strtoupper(htmlspecialchars($order['courier'])) ?>
                                    <?php if (!empty($order['service'])): ?>
                                        - <?= htmlspecialchars($order['service']) ?>
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
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
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

                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pembayaran</h5>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkir</span>
                            <strong>Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <h5>Total</h5>
                            <h5 class="text-primary">Rp <?= number_format($order['total'], 0, ',', '.') ?></h5>
                        </div>

                    </div>

                </div>

                <div class="card shadow-sm">

                    <div class="card-header">
                        <h5 class="mb-0">Ubah Status Pesanan</h5>
                    </div>

                    <div class="card-body">

                        <form action="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>/status" method="POST">

                            <div class="mb-3">
                                <select name="status" class="form-select">
                                    <?php foreach ($statusMap as $key => $info): ?>
                                    <option value="<?= $key ?>" <?= $order['status'] === $key ? 'selected' : '' ?>>
                                        <?= $info['label'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Simpan Status
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

<?php

require_once "../app/views/layouts/admin_footer.php";

?>