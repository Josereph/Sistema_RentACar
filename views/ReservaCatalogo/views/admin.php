<?php
// Esta vista entra por router (index.php). Ya existen PROJECT_ROOT_FS y url().
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

  <?php include PROJECT_ROOT_FS . '/views/layouts/navbar.php'; ?>

  <div class="container py-4">
    <h2 class="mb-3">Panel de Administración</h2>

    <div class="row mt-4 g-2">
      <div class="col-md-4">
        <a href="<?= url('index.php?controller=Clientes&action=index') ?>"
           class="btn btn-primary w-100">
          Gestión de Clientes
        </a>
      </div>

      <div class="col-md-4">
        <a href="<?= url('index.php?controller=Devolucion&action=index') ?>"
           class="btn btn-success w-100">
          Devoluciones
        </a>
      </div>

      <div class="col-md-4">
        <!-- id_devolucion=1 es solo para prueba; luego lo volvemos dinámico desde Devolución -->
        <a href="<?= url('index.php?controller=Checklist&action=index&id_devolucion=1') ?>"
           class="btn btn-warning w-100">
          Checklist
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>