<?php
$titulo = 'Código QR de ' . htmlspecialchars($usuario['nombre'] ?? '');
$seccion = 'asistencia';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header">
                    <h5>Código QR para <?= htmlspecialchars($usuario['nombre'] ?? '') ?></h5>
                </div>
                <div class="card-body">
                    <img src="<?= $dataUri ?? '' ?>" alt="QR Code" class="img-fluid mb-3">
                    <p><strong>ID:</strong> <?= $usuario['id_usuario'] ?? '' ?></p>
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'] ?? '') ?></p>
                    <p>El empleado debe mostrar este código a la cámara para marcar asistencia.</p>
                    <a href="<?= $dataUri ?? '' ?>" download="qr_<?= $usuario['id_usuario'] ?? '' ?>.png" class="btn btn-primary">Descargar QR</a>
                    <a href="/Sistema_RentACar/index.php?controller=Usuarios&action=index" class="btn btn-outline-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>