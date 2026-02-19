<?php
// ============================================
// BOOTSTRAP DE RUTAS (NO TOCAR)
// ============================================
if (!defined('PROJECT_ROOT_FS')) {
    $projectRoot = realpath(__DIR__ . '/../../..');
    if ($projectRoot === false) { $projectRoot = realpath(__DIR__); }
    define('PROJECT_ROOT_FS', $projectRoot);
}

if (!defined('BASE_URL')) {
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $proj = str_replace('\\', '/', PROJECT_ROOT_FS);
    $doc  = $docRoot ? str_replace('\\', '/', $docRoot) : '';

    $rel = '';
    if ($doc && strpos($proj, $doc) === 0) {
        $rel = substr($proj, strlen($doc));
    }
    $rel = '/' . trim(str_replace('\\', '/', $rel), '/');
    if ($rel === '/') { $rel = ''; }

    define('BASE_URL', $rel);
}

if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | GO CAR</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
    <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/admin.css') ?>">
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- Tu contenido de admin queda igual -->
    <?php /* ====== TU CONTENIDO ADMIN AQUÍ (SIN CAMBIOS) ====== */ ?>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/script.js') ?>"></script>
</body>
</html>
