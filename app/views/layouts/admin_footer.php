</main>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

<script
    src="<?= BASE_URL ?>/assets/js/main.js">
</script>

<?php

if (isset($pageScript)) {
    echo '<script src="' . BASE_URL . '/assets/js/' . $pageScript . '"></script>';
}

?>

</body>

</html>