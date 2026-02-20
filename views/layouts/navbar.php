<?php
// ============================================
// BOOTSTRAP DE RUTAS (NO TOCAR)
// Calcula automáticamente la URL base del proyecto, sin hardcodear /Rent-a-car ni /Sistema_RentACar
// ============================================
if (!defined('PROJECT_ROOT_FS')) {
    $projectRoot = realpath(__DIR__ . '/../../..'); // desde /views/ReservaCatalogo/views hacia la raíz del proyecto
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
    if ($rel === '/') { $rel = ''; } // proyecto en la raíz del dominio

    define('BASE_URL', $rel);
}

if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url('index.php') ?>">
            <i class="fas fa-car" style="color: #137fec;"></i> GO CAR
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= url('index.php') ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('views/ReservaCatalogo/views/reservas.php') ?>">Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sobre-nosotros">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('views/ReservaCatalogo/views/admin.php') ?>">
                        <i class="fas fa-tachometer-alt"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
