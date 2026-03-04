<?php
$titulo = 'Gestión de Clientes';
$seccion = 'clientes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

$clientes = $clientes ?? [];
$totalClientes = $totalClientes ?? 0;
$activos = $activos ?? 0;
$nuevosMes = $nuevosMes ?? 0;
$inactivos = $inactivos ?? 0;

// Función url local (por si acaso)
if (!function_exists('url')) {
    function url($path = '') {
        return '/Sistema_RentACar/' . ltrim($path, '/');
    }
}
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-users me-2" style="color: #137fec;"></i>Gestión de Clientes</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == 'creado'): ?>
                Cliente creado exitosamente.
            <?php elseif ($_GET['success'] == 'editado'): ?>
                Cliente actualizado exitosamente.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Ocurrió un error. Intente de nuevo.</div>
    <?php endif; ?>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?= $totalClientes ?></div>
                <div class="stat-label">Total Clientes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                <div class="stat-number"><?= $activos ?></div>
                <div class="stat-label">Clientes Activos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-user-plus"></i></div>
                <div class="stat-number"><?= $nuevosMes ?></div>
                <div class="stat-label">Nuevos este mes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-user-clock"></i></div>
                <div class="stat-number"><?= $inactivos ?></div>
                <div class="stat-label">Inactivos</div>
            </div>
        </div>
    </div>

    <!-- Listado y botones (siempre visibles para admin/superadmin) -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Clientes</span>
            <div>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalNuevoCliente')">
                    <i class="fas fa-plus"></i> Nuevo Cliente
                </button>
                <a href="<?= url('index.php?area=admin&controller=Clientes&action=exportarExcel') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-excel"></i> Exportar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>DUI</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Renta activa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= str_pad($c['id_cliente'], 3, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2"><?= strtoupper(substr($c['nombre'],0,1).substr($c['apellido'],0,1)) ?></div>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></div>
                                        <small class="text-muted">Registrado: <?= date('d/m/Y', strtotime($c['created_at'] ?? 'now')) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($c['DUI'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['correo'] ?? '') ?></td>
                            <td>
                                <?php
                                $db = Database::connect();
                                $stmt = $db->prepare("SELECT id_reserva FROM tbReservas WHERE id_cliente = ? AND estado = 'en_curso' LIMIT 1");
                                $stmt->execute([$c['id_cliente']]);
                                $rentaActiva = $stmt->fetch();
                                ?>
                                <?php if ($rentaActiva): ?>
                                    <span class="badge badge-pending"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>En curso</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sin renta</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editCliente(<?= $c['id_cliente'] ?>)" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCliente(<?= $c['id_cliente'] ?>)" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-outline-info" title="Ver historial">
                                    <i class="fas fa-eye"></i>
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

<!-- MODALES (siempre disponibles) -->
<div class="modal-overlay" id="modalNuevoCliente">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Cliente</div>
            <button class="modal-close" onclick="closeModal('modalNuevoCliente')"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= url('index.php?area=admin&controller=Clientes&action=crear') ?>" method="POST">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input class="form-control" type="text" name="nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input class="form-control" type="text" name="apellido" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DUI *</label>
                        <input class="form-control" type="text" name="dui" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="tel" name="telefono">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <input class="form-control" type="text" name="direccion">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="closeModal('modalNuevoCliente')">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalEditCliente">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title"><i class="fas fa-pen-to-square me-2"></i>Editar Cliente</div>
            <button class="modal-close" onclick="closeModal('modalEditCliente')"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= url('index.php?area=admin&controller=Clientes&action=editar') ?>" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input class="form-control" type="text" name="nombre" id="edit_nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input class="form-control" type="text" name="apellido" id="edit_apellido" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DUI</label>
                        <input class="form-control" type="text" name="dui" id="edit_dui">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" type="tel" name="telefono" id="edit_telefono">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" id="edit_email">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <input class="form-control" type="text" name="direccion" id="edit_direccion">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="closeModal('modalEditCliente')">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Actualizar</button>
            </div>
        </form>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

<script>
function editCliente(id) {
    fetch('<?= url('index.php?area=admin&controller=Clientes&action=getJson&id=') ?>' + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la petición');
            }
            return response.json();
        })
        .then(d => {
            document.getElementById('edit_id').value = d.id_cliente;
            document.getElementById('edit_nombre').value = d.nombre;
            document.getElementById('edit_apellido').value = d.apellido;
            document.getElementById('edit_dui').value = d.DUI;
            document.getElementById('edit_telefono').value = d.telefono || '';
            document.getElementById('edit_email').value = d.correo || '';
            document.getElementById('edit_direccion').value = d.direccion || '';
            openModal('modalEditCliente');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cargar la información del cliente.');
        });
}

function deleteCliente(id) {
    if (confirm('¿Está seguro de eliminar este cliente?')) {
        window.location.href = '<?= url('index.php?area=admin&controller=Clientes&action=eliminar&id=') ?>' + id;
    }
}
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>