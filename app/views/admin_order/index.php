<?php

require_once "../app/views/layouts/admin_header.php";

$statusMap = [
    'pending' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-warning text-dark'],
    'processing' => ['label' => 'Diproses', 'class' => 'bg-info text-dark'],
    'shipping' => ['label' => 'Dikirim', 'class' => 'bg-primary'],
    'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
];

$currentStatus = $_GET['status'] ?? '';

?>

<h2 class="fw-bold mb-4">Kelola Pesanan</h2>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/orders" class="nav-link <?= $currentStatus === '' ? 'active' : '' ?>">
                            Semua
                        </a>
                    </li>
                    <?php foreach ($statusMap as $key => $info): ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>/admin/orders?status=<?= $key ?>" class="nav-link <?= $currentStatus === $key ? 'active' : '' ?>">
                            <?= $info['label'] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <?php if (empty($orders)): ?>

                    <p class="text-muted text-center py-5 mb-0">Belum ada pesanan.</p>

                <?php else: ?>

                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Kode Pesanan</th>
                                <th>Pembeli</th>
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
                                <td>
                                    <?= htmlspecialchars($order['buyer_name']) ?>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($order['buyer_email']) ?></small>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                <td><span class="badge <?= $status['class'] ?>"><?= $status['label'] ?></span></td>
                                <td class="text-end">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                <td class="text-end pe-3">
                                    <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
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