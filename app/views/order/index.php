<?php

require_once "../app/views/layouts/header.php";

//------------------------------------------------
// mapping status ke label & warna badge
//------------------------------------------------
$statusMap = [
    'pending' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-warning text-dark'],
    'processing' => ['label' => 'Diproses', 'class' => 'bg-info text-dark'],
    'shipping' => ['label' => 'Dikirim', 'class' => 'bg-primary'],
    'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
];

?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">Pesanan Saya</h2>
        <p class="text-muted">Riwayat seluruh pesanan yang pernah Anda buat.</p>
    </div>
</div>

<?php if (empty($orders)): ?>

    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-3">Anda belum memiliki pesanan.</p>
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary">
                Mulai Belanja
            </a>
        </div>
    </div>

<?php else: ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Kode Pesanan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <?php $status = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary']; ?>
                    <tr>
                        <td class="ps-3"><?= htmlspecialchars($order['order_code']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <span class="badge <?= $status['class'] ?>">
                                <?= $status['label'] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            Rp <?= number_format($order['total'], 0, ',', '.') ?>
                        </td>
                        <td class="text-end pe-3">
                            <a href="<?= BASE_URL ?>/order/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?php

require_once "../app/views/layouts/footer.php";

?>