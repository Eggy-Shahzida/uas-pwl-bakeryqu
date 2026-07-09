<?php

require_once "../app/views/layouts/admin_header.php";

$statusMap = [
    'pending' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-warning text-dark'],
    'processing' => ['label' => 'Diproses', 'class' => 'bg-info text-dark'],
    'shipping' => ['label' => 'Dikirim', 'class' => 'bg-primary'],
    'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
];

?>

<h2 class="fw-bold mb-4">Dashboard</h2>

        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Pesanan</div>
                        <div class="fs-3 fw-bold"><?= $totalOrders ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Pendapatan (Selesai)</div>
                        <div class="fs-4 fw-bold text-success">
                            Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Produk Aktif</div>
                        <div class="fs-3 fw-bold"><?= $totalActiveProducts ?></div>
                        <?php if ($lowStockProducts > 0): ?>
                        <div class="small text-danger mt-1">
                            <?= $lowStockProducts ?> produk stok menipis
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Pelanggan</div>
                        <div class="fs-3 fw-bold"><?= $totalCustomers ?></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-3 mb-4 row-cols-5">

            <div class="col">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="small text-muted">Menunggu</div>
                        <div class="fs-5 fw-bold"><?= $pendingOrders ?></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="small text-muted">Diproses</div>
                        <div class="fs-5 fw-bold"><?= $processingOrders ?></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="small text-muted">Dikirim</div>
                        <div class="fs-5 fw-bold"><?= $shippingOrders ?></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="small text-muted">Selesai</div>
                        <div class="fs-5 fw-bold"><?= $completedOrders ?></div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="small text-muted">Ditolak</div>
                        <div class="fs-5 fw-bold"><?= $rejectedOrders ?></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesanan Terbaru</h5>
                <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="card-body p-0">

                <?php if (empty($recentOrders)): ?>

                    <p class="text-muted text-center py-4 mb-0">Belum ada pesanan.</p>

                <?php else: ?>

                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Kode Pesanan</th>
                                <th>Pembeli</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <?php $status = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary']; ?>
                            <tr>
                                <td class="ps-3">
                                    <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>">
                                        <?= htmlspecialchars($order['order_code']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                <td><span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span></td>
                                <td class="text-end pe-3">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>

            </div>

        </div>

<?php

require_once "../app/views/layouts/admin_footer.php";

?>