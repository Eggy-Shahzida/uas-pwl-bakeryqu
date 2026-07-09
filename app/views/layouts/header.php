<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= APP_NAME ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/assets/css/style.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

</head>

<body>

<?php require_once "../app/views/partials/navbar.php"; ?>
<?php if (isset($_SESSION['success'])) : ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">

        <?= htmlspecialchars($_SESSION['success']) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['error'])) : ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <?= htmlspecialchars($_SESSION['error']) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

<main class="container py-4">
    <!-- Flash Success -->
<?php if (isset($_SESSION['success'])) : ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <?= htmlspecialchars($_SESSION['success']) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <!-- Flash Error -->
    <?php if (isset($_SESSION['error'])) : ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['error']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>