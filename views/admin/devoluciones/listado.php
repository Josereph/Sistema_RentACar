<?php
$titulo = 'Listado de Devoluciones';
$seccion = 'devoluciones';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-undo-alt me-2" style="color: #137fec;"></i>Devoluciones Registradas</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Devoluciones</span>
            <a href="/Sistema_RentACar/index.php?controller=Devolucion&action=index" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Devolución
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Fecha Real</th>
                            <th>KM Final</th>
                            <th>Estado</th>
                            <th>Checklist</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devoluciones as $d): ?>
                        <tr>
                            <td><?= $d['id_devolucion'] ?></td>
                            <td><?= htmlspecialchars($d['cliente_nombre'] . ' ' . $d['cliente_apellido']) ?></td>
                            <td><?= htmlspecialchars($d['marca'] . ' ' . $d['modelo'] . ' (' . $d['numero_placa'] . ')') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($d['fecha_devolucion_real'])) ?></td>
                            <td><?= $d['km_final'] ?></td>
                            <td>
                                <span class="badge <?= $d['estado'] == 'ok' ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ucfirst($d['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/Sistema_RentACar/index.php?controller=Checklist&action=index&id_devolucion=<?= $d['id_devolucion'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-clipboard-check"></i> Ver Checklist
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
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>