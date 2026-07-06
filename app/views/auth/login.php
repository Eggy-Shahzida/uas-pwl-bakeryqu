<?php

$pageTitle = "Login";
$pageScript = "auth.js";

require_once "../app/views/layouts/header.php";

?>

<div class="row justify-content-center">

    <div class="col-lg-5 col-md-7">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <h2 class="text-center fw-bold mb-3">
                    Login
                </h2>

                <p class="text-center text-muted mb-4">
                    Masuk ke akun BakeryQu Anda.
                </p>

                <form action="<?= BASE_URL ?>/login" method="POST">

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

                    <div class="mb-4">

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

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        Login

                    </button>

                </form>

                <hr>

                <p class="text-center mb-0">

                    Belum memiliki akun?

                    <a href="<?= BASE_URL ?>/register">

                        Daftar di sini

                    </a>

                </p>

            </div>

        </div>

    </div>

</div>

<?php

require_once "../app/views/layouts/footer.php";

?>