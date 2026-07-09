<?php

require_once "../app/views/layouts/admin_header.php";

// Data lama diambil dari session (kalau validasi sebelumnya gagal),
// kalau tidak ada, pakai data produk dari database
$formData = !empty($old) ? $old : $product;

?>

        <div class="d-flex align-items-center mb-4">
            <a href="<?= BASE_URL ?>/admin/products" class="me-3 text-decoration-none">&larr;</a>
            <h2 class="fw-bold mb-0">Edit Produk</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="<?= BASE_URL ?>/admin/products/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-8">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($formData['name']) ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (int) $formData['category_id'] === (int) $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input
                                        type="number"
                                        name="price"
                                        class="form-control"
                                        min="0"
                                        step="100"
                                        value="<?= htmlspecialchars($formData['price']) ?>"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Berat (gram)</label>
                                    <input
                                        type="number"
                                        name="weight"
                                        class="form-control"
                                        min="1"
                                        value="<?= htmlspecialchars($formData['weight']) ?>"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stok</label>
                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control"
                                        min="0"
                                        value="<?= htmlspecialchars($formData['stock']) ?>"
                                        required>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= $formData['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="inactive" <?= $formData['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini</label>

                                <?php if (!empty($product['image'])): ?>
                                    <img
                                        src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                        class="d-block mb-2"
                                        style="width: 100%; max-width: 200px; border-radius: 8px; object-fit: cover;">
                                <?php else: ?>
                                    <p class="text-muted">Belum ada gambar.</p>
                                <?php endif; ?>

                                <label class="form-label">Ganti Gambar (opsional)</label>
                                <input
                                    type="file"
                                    name="image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                            </div>

                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline-secondary">
                        Batal
                    </a>

                </form>

            </div>
        </div>

<?php

require_once "../app/views/layouts/admin_footer.php";

?>