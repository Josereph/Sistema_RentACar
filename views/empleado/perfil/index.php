<?php
$titulo = 'Mi Perfil';
$seccion = 'perfil';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Perfil del Empleado</h4>
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['admin_nombre'] ?? '') ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($_SESSION['admin_rol'] ?? '') ?></p>
            <!-- Puedes agregar más información si la tienes en la base de datos -->
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_footer.php'; ?>