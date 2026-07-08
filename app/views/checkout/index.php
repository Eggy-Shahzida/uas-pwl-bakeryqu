<?php

$pageScript = "checkout.js";

require_once "../app/views/layouts/header.php";

?>

<script>
    // Data untuk checkout.js (tidak bisa ditulis langsung di file JS statis)
    window.CHECKOUT_SUBTOTAL = <?= (int) $subtotal ?>;
    window.BASE_URL = window.BASE_URL || "<?= BASE_URL ?>";
</script>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold">Checkout</h2>
        <p class="text-muted">Lengkapi data penerima sebelum melanjutkan pembayaran.</p>
    </div>
</div>

<form action="<?= BASE_URL ?>/checkout" method="POST">

<div class="row">

    <div class="col-lg-7">

        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h5 class="mb-0">Data Penerima</h5>
            </div>

            <div class="card-body">

                <?php $old = $_SESSION['old'] ?? []; unset($_SESSION['old']); ?>

                <div class="mb-3">
                    <label class="form-label">Nama Penerima</label>
                    <input
                        type="text"
                        name="receiver_name"
                        class="form-control"
                        value="<?= htmlspecialchars($old['receiver_name'] ?? $_SESSION['user']['name']) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="08xxxxxxxxxx"
                        value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                        required>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Provinsi</label>
                        <select name="province_id" id="province" class="form-select" required>
                            <option value="">-- Pilih Provinsi --</option>
                            <?php foreach ($provinces as $province): ?>
                            <option value="<?= htmlspecialchars($province['id']) ?>">
                                <?= htmlspecialchars($province['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kota / Kabupaten</label>
                        <select name="city_id" id="city" class="form-select" required disabled>
                            <option value="">-- Pilih Kota/Kabupaten --</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select name="district_id" id="district" class="form-select" required disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Nama jalan, nomor rumah, RT/RW, patokan, dll."
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kurir</label>
                    <select name="courier" id="courier" class="form-select">
                        <option value="jne">JNE</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="jnt">J&amp;T</option>
                        <option value="anteraja">AnterAja</option>
                        <option value="ninja">Ninja Xpress</option>
                    </select>
                </div>

                <!-- dikirim ke server saat submit, diisi otomatis oleh checkout.js -->
                <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                <input type="hidden" name="shipping_service" id="shippingServiceInput" value="">
                <input type="hidden" name="province_name" id="provinceNameInput" value="">
                <input type="hidden" name="city_name" id="cityNameInput" value="">
                <input type="hidden" name="district_name" id="districtNameInput" value="">

            </div>

        </div>

    </div>

    <div class="col-lg-5">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Ringkasan Belanja</h5>
            </div>

            <div class="card-body">

                <?php foreach($items as $item): ?>

                <div class="d-flex justify-content-between mb-2">

                    <div>

                        <strong><?= htmlspecialchars($item['name']) ?></strong><br>

                        <small class="text-muted">
                            <?= $item['quantity'] ?> x Rp <?= number_format($item['price'],0,',','.') ?>
                        </small>

                    </div>

                    <strong>
                        Rp <?= number_format($item['subtotal'],0,',','.') ?>
                    </strong>

                </div>

                <hr>

                <?php endforeach; ?>

                <div class="d-flex justify-content-between">

                    <span>Subtotal</span>

                    <strong>
                        Rp <?= number_format($subtotal,0,',','.') ?>
                    </strong>

                </div>

                <div class="d-flex justify-content-between mt-2">

                    <span>Ongkir</span>

                    <strong id="shippingCost">
                        -
                    </strong>

                </div>

                <hr>

                <div class="d-flex justify-content-between">

                    <h5>Total</h5>

                    <h5 class="text-primary" id="grandTotal">
                        Rp <?= number_format($subtotal,0,',','.') ?>
                    </h5>

                </div>

                <button
                    class="btn btn-success w-100 mt-3"
                    type="submit"
                    id="submitOrderBtn">

                    Buat Pesanan

                </button>

                <small class="text-muted d-block mt-2">
                    * Lengkapi provinsi, kota, dan kecamatan untuk melihat ongkos kirim.
                </small>

            </div>

        </div>

    </div>

</div>

</form>

<?php

require_once "../app/views/layouts/footer.php";

?>