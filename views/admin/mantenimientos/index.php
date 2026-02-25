<?php
$titulo = 'Gestión de Mantenimientos';
$seccion = 'mantenimientos';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-wrench me-2" style="color: #137fec;"></i>Gestión de Mantenimientos</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['success'] == 'creado') echo 'Mantenimiento creado exitosamente.';
            elseif ($_GET['success'] == 'editado') echo 'Mantenimiento actualizado.';
            elseif ($_GET['success'] == 'eliminado') echo 'Mantenimiento eliminado.';
            elseif ($_GET['success'] == 'finalizado') echo 'Mantenimiento marcado como finalizado.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Mantenimientos</span>
            <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=nuevo" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Mantenimiento
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vehículo</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Costo</th>
                            <th>KM</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mantenimientos as $m): ?>
                        <tr>
                            <td><?= $m['id_mantenimiento'] ?></td>
                            <td><?= htmlspecialchars($m['marca'] . ' ' . $m['modelo'] . ' (' . $m['numero_placa'] . ')') ?></td>
                            <td><?= ucfirst($m['tipo']) ?></td>
                            <td><?= htmlspecialchars($m['descripcion']) ?></td>
                            <td><?= date('d/m/Y', strtotime($m['fecha_inicio'])) ?></td>
                            <td><?= $m['fecha_fin'] ? date('d/m/Y', strtotime($m['fecha_fin'])) : '-' ?></td>
                            <td>$<?= number_format($m['costo'], 2) ?></td>
                            <td><?= $m['km_actual'] ?? '-' ?></td>
                            <td>
                                <?php
                                $clase = match($m['estado']) {
                                    'programado' => 'badge-pending',
                                    'en_proceso' => 'badge-active',
                                    'finalizado' => 'badge-inactive',
                                    default => ''
                                };
                                ?>
                                <span class="badge <?= $clase ?>"><?= ucfirst($m['estado']) ?></span>
                            </td>
                            <td>
                                <?php if ($m['estado'] != 'finalizado'): ?>
                                    <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=finalizar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como finalizado?')" title="Finalizar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=editar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=eliminar&id=<?= $m['id_mantenimiento'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar este mantenimiento?')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
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