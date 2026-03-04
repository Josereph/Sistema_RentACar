<?php
$titulo = 'Gestión de Clientes';
$seccion = 'clientes';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_navbar.php';

$clientes = $clientes ?? [];
$totalClientes = $totalClientes ?? 0;
$activos = $activos ?? 0;
$nuevosMes = $nuevosMes ?? 0;
$inactivos = $inactivos ?? 0;
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-users me-2" style="color: #137fec;"></i>Gestión de Clientes</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == 'creado'): ?>Cliente creado exitosamente.<?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Ocurrió un error. Intente de nuevo.</div>
    <?php endif; ?>

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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Clientes</span>
            <a href="<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=nuevo') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </a>
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
                            <td><?= htmlspecialchars($c['DUI']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= htmlspecialchars($c['correo']) ?></td>
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
                                <button class="btn btn-sm btn-outline-info" onclick="verCliente(<?= $c['id_cliente'] ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalVerCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleCliente">
                Cargando...
            </div>
        </div>
    </div>
</div>

<script>
function verCliente(id) {
    fetch('<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=ver&id=') ?>' + id)
        .then(r => r.json())
        .then(d => {
            let html = `
                <p><strong>ID:</strong> ${d.id_cliente}</p>
                <p><strong>Nombre:</strong> ${d.nombre} ${d.apellido}</p>
                <p><strong>DUI:</strong> ${d.DUI}</p>
                <p><strong>Teléfono:</strong> ${d.telefono || 'N/A'}</p>
                <p><strong>Email:</strong> ${d.correo || 'N/A'}</p>
                <p><strong>Dirección:</strong> ${d.direccion || 'N/A'}</p>
                <p><strong>Registro:</strong> ${d.created_at}</p>
            `;
            document.getElementById('detalleCliente').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalVerCliente')).show();
        });
}
</script>

<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_footer.php'; ?>