<?php
$titulo = 'Gestión de Vehículos';
$seccion = 'vehiculos';
include PROJECT_ROOT_FS . '/views/empleado/layouts/header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/navbar.php';

$vehiculos = $vehiculos ?? [];
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-car me-2" style="color: #137fec;"></i>Gestión de Vehículos</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == 'creado'): ?>Vehículo creado exitosamente.<?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Vehículos</span>
            <a href="<?= url('index.php?area=empleado&controller=VehiculosEmpleado&action=nuevo') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Vehículo
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca/Modelo</th>
                            <th>Año</th>
                            <th>Placa</th>
                            <th>Precio/día</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vehiculos as $v): ?>
                        <tr>
                            <td><?= $v['id_vehiculo'] ?></td>
                            <td>
                                <?php 
                                $imagenes = Vehiculo::getImagenes($v['id_vehiculo']);
                                if (!empty($imagenes)): 
                                ?>
                                    <img src="<?= $imagenes[0] ?>" width="50" height="50" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($v['car_name']) ?></td>
                            <td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo']) ?></td>
                            <td><?= $v['year'] ?></td>
                            <td><?= htmlspecialchars($v['numero_placa']) ?></td>
                            <td>$<?= number_format($v['precio_dia'], 2) ?></td>
                            <td>
                                <span class="badge <?= match($v['estado']) {
                                    'disponible' => 'badge-active',
                                    'rentado' => 'badge-inactive',
                                    'mantenimiento' => 'badge-pending',
                                    default => ''
                                } ?>">
                                    <?= ucfirst($v['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($v['estado'] != 'mantenimiento'): ?>
                                    <a href="<?= url('index.php?area=empleado&controller=MantenimientosEmpleado&action=nuevo&id_vehiculo=' . $v['id_vehiculo']) ?>" class="btn btn-sm btn-outline-warning" title="Enviar a mantenimiento">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">En mantenimiento</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/footer.php'; ?>