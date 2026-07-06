<?php

$pageTitle = "Register";
$pageScript = "auth.js";

require_once "../app/views/layouts/header.php";

?>

<div class="row justify-content-center">

    <div class="col-lg-6 col-md-8">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <h2 class="text-center fw-bold mb-3">
                    Register
                </h2>

                <p class="text-center text-muted mb-4">
                    Buat akun BakeryQu baru.
                </p>

                <form action="<?= BASE_URL ?>/register" method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            placeholder="Konfirmasi password"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-success w-100">

                        Daftar

                    </button>

                </form>

                <hr>

                <p class="text-center mb-0">

                    Sudah memiliki akun?

                    <a href="<?= BASE_URL ?>/login">

                        Login di sini

                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

<?php

require_once "../app/views/layouts/footer.php";

?>