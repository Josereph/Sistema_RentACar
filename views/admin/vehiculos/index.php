<?php
$titulo = 'Gestión de Vehículos';
$seccion = 'vehiculos';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

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
            <?php if ($_GET['success'] == 'creado'): ?>Vehículo creado exitosamente.
            <?php elseif ($_GET['success'] == 'editado'): ?>Vehículo actualizado exitosamente.
            <?php elseif ($_GET['success'] == 'eliminado'): ?>Vehículo eliminado.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Vehículos</span>
            <a href="/Sistema_RentACar/index.php?controller=Vehiculos&action=form" class="btn btn-primary btn-sm">
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
                                <a href="/Sistema_RentACar/index.php?controller=Vehiculos&action=form&id=<?= $v['id_vehiculo'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($v['estado'] != 'mantenimiento'): ?>
                                    <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=nuevo&id_vehiculo=<?= $v['id_vehiculo'] ?>" class="btn btn-sm btn-outline-warning" title="Enviar a mantenimiento">
                                        <i class="fas fa-wrench"></i>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarVehiculo(<?= $v['id_vehiculo'] ?>)" title="Eliminar">
                                    <i class="fas fa-trash"></i>
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

<script>
function eliminarVehiculo(id) {
    if (confirm('¿Está seguro de eliminar este vehículo?')) {
        window.location.href = '/Sistema_RentACar/index.php?controller=Vehiculos&action=eliminar&id=' + id;
    }
}
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>