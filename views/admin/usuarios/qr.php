<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: ' . url('index.php?controller=Auth&action=login'));
    exit;
}
$titulo = 'Gestión de Usuarios';
$seccion = 'usuarios';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

$usuarios = $usuarios ?? [];
$roles = $roles ?? [];
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-users-cog me-2" style="color: #137fec;"></i>Gestión de Usuarios</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Operación exitosa.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Usuarios</span>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalNuevoUsuario')">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($u['nombre']) ?></td>
                            <td><?= htmlspecialchars($u['correo']) ?></td>
                            <td><?= htmlspecialchars($u['rol_nombre']) ?></td>
                            <td>
                                <span class="badge <?= $u['estado'] == 'activo' ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ucfirst($u['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editUsuario(<?= $u['id_usuario'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUsuario(<?= $u['id_usuario'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="/Sistema_RentACar/index.php?controller=AsistenciaAdmin&action=qr&id=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-outline-info" title="Generar QR">
                                    <i class="fas fa-qrcode"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO USUARIO -->
<div class="modal-overlay" id="modalNuevoUsuario">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Usuario</div>
            <button class="modal-close" onclick="closeModal('modalNuevoUsuario')"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= url('index.php?controller=Usuarios&action=crear') ?>" method="POST">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo *</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol *</label>
                    <select name="id_rol" class="form-control" required>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_rol'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="closeModal('modalNuevoUsuario')">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR USUARIO -->
<div class="modal-overlay" id="modalEditUsuario">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-user-edit me-2"></i>Editar Usuario</div>
            <button class="modal-close" onclick="closeModal('modalEditUsuario')"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= url('index.php?controller=Usuarios&action=editar') ?>" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo *</label>
                    <input type="email" name="correo" id="edit_correo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nueva contraseña (dejar vacío si no cambia)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rol *</label>
                    <select name="id_rol" id="edit_id_rol" class="form-control" required>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_rol'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" id="edit_estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="closeModal('modalEditUsuario')">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editUsuario(id) {
    fetch('<?= url('index.php?controller=Usuarios&action=getJson&id=') ?>' + id)
        .then(r => r.json())
        .then(d => {
            document.getElementById('edit_id').value = d.id_usuario;
            document.getElementById('edit_nombre').value = d.nombre;
            document.getElementById('edit_correo').value = d.correo;
            document.getElementById('edit_id_rol').value = d.id_rol;
            document.getElementById('edit_estado').value = d.estado;
            openModal('modalEditUsuario');
        });
}
function deleteUsuario(id) {
    if (confirm('¿Está seguro de eliminar este usuario?')) {
        window.location.href = '<?= url('index.php?controller=Usuarios&action=eliminar&id=') ?>' + id;
    }
}
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>