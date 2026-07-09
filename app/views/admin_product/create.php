<?php

require_once "../app/views/layouts/admin_header.php";

$old = $old ?? [];

?>

        <div class="d-flex align-items-center mb-4">
            <a href="<?= BASE_URL ?>/admin/products" class="me-3 text-decoration-none">&larr;</a>
            <h2 class="fw-bold mb-0">Tambah Produk</h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="<?= BASE_URL ?>/admin/products/store" method="POST" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-8">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (int) ($old['category_id'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
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
                                        value="<?= htmlspecialchars($old['price'] ?? '') ?>"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Berat (gram)</label>
                                    <input
                                        type="number"
                                        name="weight"
                                        class="form-control"
                                        min="1"
                                        value="<?= htmlspecialchars($old['weight'] ?? '') ?>"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stok</label>
                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control"
                                        min="0"
                                        value="<?= htmlspecialchars($old['stock'] ?? 0) ?>"
                                        required>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                <input
                                    type="file"
                                    name="image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    required>
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
                            </div>

                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Produk
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