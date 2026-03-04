<?php
// views/ReservaCatalogo/views/mis_reservas.php
session_start();

// Definir constantes (igual que en catálogo)
if (!defined('APP_ROOT')) define('APP_ROOT', '/Sistema_RentACar');
if (!function_exists('url')) {
  define('BASE_URL', APP_ROOT);
  function url($path = '') { return BASE_URL . '/' . ltrim($path, '/'); }
}

if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/db.php';

$pdo = Database::connect();

// Verificar que el cliente está logueado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: ' . url('views/auth/login.php'));
    exit;
}

$id_cliente = $_SESSION['cliente_id'];

// Obtener reservas del cliente con datos del vehículo
$sql = "SELECT r.*, v.marca, v.modelo, v.year, v.numero_placa,
               TIMESTAMPDIFF(DAY, r.fecha_recogida, r.fecha_entrega) as dias_totales
        FROM tbReservas r
        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
        WHERE r.id_cliente = ?
        ORDER BY r.fecha_reserva DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_cliente]);
$reservas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas | GO CAR</title>

  <!-- Vendor (los mismos que en catálogo) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tus estilos -->
  <link rel="stylesheet" href="<?= url('assets/css/client.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/reservas.css') ?>">
</head>
<body class="gocar-body">
  <!-- Incluir el navbar que ya funciona en el catálogo -->
  <?php include __DIR__ . '/../../layouts/navbar.php'; ?>

  <section class="reservas-page py-5">
    <div class="container">
      <h1 class="mb-4">Mis Reservas</h1>

      <?php if (empty($reservas)): ?>
        <div class="alert alert-info">
          No tienes reservas aún. <a href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">Explora nuestro catálogo</a>.
        </div>
      <?php else: ?>
        <div class="row g-4">
          <?php foreach ($reservas as $r): ?>
            <div class="col-md-6 col-lg-4">
              <!-- Reutilizamos la misma tarjeta que en el catálogo para consistencia -->
              <div class="car-card">
                <div class="car-info p-4">
                  <h4 class="car-title"><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo']) ?></h4>
                  <p class="car-meta">Placa: <?= htmlspecialchars($r['numero_placa']) ?> • <?= $r['year'] ?></p>

                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="car-price">$<?= number_format($r['precio_total'], 2) ?></span>
                    <span class="badge bg-<?= $r['estado'] == 'pendiente' ? 'warning' : ($r['estado'] == 'confirmada' ? 'success' : ($r['estado'] == 'cancelada' ? 'danger' : 'secondary')) ?>">
                      <?= $r['estado'] ?>
                    </span>
                  </div>

                  <p class="text-muted small">
                    <i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($r['fecha_recogida'])) ?> - <?= date('d/m/Y', strtotime($r['fecha_entrega'])) ?>
                  </p>

                  <?php if ($r['estado'] == 'pendiente'): ?>
                    <a href="<?= url('views/ReservaCatalogo/views/pagos.php?reserva_id=' . $r['id_reserva']) ?>" class="btn btn-book-now w-100">
                      Completar pago
                    </a>
                  <?php elseif ($r['estado'] == 'confirmada'): ?>
                    <a href="#" class="btn btn-outline-secondary w-100 disabled">Reserva confirmada</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Incluir el footer que ya funciona en el catálogo -->
  <?php include __DIR__ . '/../../layouts/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Tus scripts -->
  <script src="<?= url('assets/js/client-ui.js') ?>"></script>
  <script src="<?= url('assets/js/ReservaCatalogo/reservas.js') ?>"></script>
</body>
</html>