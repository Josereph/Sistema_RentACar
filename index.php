<?php
define('BASE_URL', '/Sistema_RentACar');
function url($path = '') { return rtrim(BASE_URL,'/') . '/' . ltrim($path,'/'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GO CAR | Tu plataforma de alquiler de vehículos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
</head>
<body>

    <?php include __DIR__ . '/views/ReservaCatalogo/views/navbar.php'; ?>

    <section class="hero-section py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a GO CAR</h1>
            <p class="lead mb-4">Encuentra el vehículo perfecto para tu viaje</p>
            <a href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>" class="btn btn-primary">Explorar Catálogo</a>
        </div>
    </section>

    <?php include __DIR__ . '/views/ReservaCatalogo/views/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/script.js') ?>"></script>
</body>
</html>
