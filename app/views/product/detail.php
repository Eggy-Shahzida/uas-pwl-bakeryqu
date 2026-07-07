<?php

$pageScript = "product-detail.js";

require_once "../app/views/layouts/header.php";

?>

<!-- Breadcrumb -->

<nav aria-label="breadcrumb" class="mb-4">

    <ol class="breadcrumb">

        <li class="breadcrumb-item">

            <a href="<?= BASE_URL ?>">
                Home
            </a>

        </li>

        <li class="breadcrumb-item">

            <a href="<?= BASE_URL ?>/products">
                Produk
            </a>

        </li>

        <li class="breadcrumb-item active">

            <?= htmlspecialchars($product['name']) ?>

        </li>

    </ol>

</nav>


<div class="row">

    <!-- FOTO -->

    <div class="col-lg-5 mb-4">

        <div class="card shadow-sm">

            <img
                src="<?= BASE_URL ?>/assets/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                class="img-fluid rounded"
                alt="<?= htmlspecialchars($product['name']) ?>">

        </div>

    </div>


    <!-- INFORMASI -->

    <div class="col-lg-7">

        <span class="badge bg-secondary mb-2">

            <?= htmlspecialchars($product['category_name']) ?>

        </span>

        <h2 class="fw-bold">

            <?= htmlspecialchars($product['name']) ?>

        </h2>

        <h3 class="text-primary fw-bold mt-3">

            Rp <?= number_format($product['price'],0,',','.') ?>

        </h3>

        <hr>

        <table class="table table-borderless">

            <tr>

                <th width="150">

                    Berat

                </th>

                <td>

                    <?= number_format($product['weight']) ?> gram

                </td>

            </tr>

            <tr>

                <th>

                    Stok

                </th>

                <td>

                    <?= $product['stock'] ?>

                </td>

            </tr>

        </table>


        <!-- Quantity -->

        <div class="mb-4">

            <label class="form-label fw-semibold">

                Jumlah

            </label>

            <div class="input-group" style="width:170px;">

                <button
                    class="btn btn-outline-secondary"
                    id="btnMinus">

                    -

                </button>

                <input
                    type="text"
                    id="quantity"
                    data-stock="<?= $product['stock'] ?>"
                    class="form-control text-center"
                    value="1"
                    readonly>

                <button
                    class="btn btn-outline-secondary"
                    id="btnPlus">

                    +

                </button>

            </div>

        </div>


        <button
            class="btn btn-primary btn-lg"
            id="btnAddCart">
            <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>

            <i class="bi bi-cart-plus"></i>

            Tambah ke Keranjang

        </button>
        <?php if($product['stock'] <= 0): ?>

            <div class="text-danger mt-2">

                Produk sedang habis.

            </div>

        <?php endif; ?>

    </div>

</div>


<hr class="my-5">


<h4 class="fw-bold mb-3">

    Deskripsi Produk

</h4>

<p style="text-align:justify;">

    <?= nl2br(htmlspecialchars($product['description'])) ?>

</p>

<?php

require_once "../app/views/layouts/footer.php";

?>