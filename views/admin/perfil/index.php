<?php
$titulo = 'Mi Perfil';
$seccion = 'perfil';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

$usuario = $usuario ?? [];
$error = $_SESSION['error_perfil'] ?? null;
$success = $_SESSION['success_perfil'] ?? null;
unset($_SESSION['error_perfil'], $_SESSION['success_perfil']);
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-id-card me-2" style="color: #137fec;"></i>Mi Perfil</h2>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (empty($usuario)): ?>
        <div class="alert alert-warning">No se pudo cargar la información del usuario.</div>
    <?php else: ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-user-circle fa-5x mb-3" style="color: #137fec;"></i>
                    <h4><?= htmlspecialchars($usuario['nombre'] ?? '') ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($usuario['rol_nombre'] ?? '') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>Editar información
                </div>
                <div class="card-body">
                    <form action="/Sistema_RentACar/index.php?controller=Perfil&action=actualizar" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva contraseña (dejar vacío si no desea cambiarla)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar nueva contraseña</label>
                            <input type="password" name="confirmar" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>