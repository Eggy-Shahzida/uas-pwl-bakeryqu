<?php

require_once "../app/views/layouts/admin_header.php";

?>


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Kelola Produk</h2>
            <a href="<?= BASE_URL ?>/admin/products/create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Produk
            </a>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/admin/products" method="GET" class="row g-2">
                    <div class="col-md-6">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nama produk..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="0">Semua Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (int) ($_GET['category'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-secondary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <?php if (empty($products)): ?>

                    <p class="text-muted text-center py-5 mb-0">Belum ada produk.</p>

                <?php else: ?>

                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="ps-3">
                                    <?php if (!empty($product['image'])): ?>
                                        <img
                                            src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <div class="bg-secondary-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 6px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                <td class="text-end">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?= $product['stock'] ?>
                                    <?php if ($product['stock'] <= 5): ?>
                                        <span class="badge bg-danger-subtle text-danger">Menipis</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($product['status'] === 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                    <form
                                        action="<?= BASE_URL ?>/admin/products/delete/<?= $product['id'] ?>"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
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