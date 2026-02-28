<?php
$titulo = 'Gestión de Reservas';
$seccion = 'reservas';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

$reservas = $reservas ?? [];
$rol = $_SESSION['admin_rol'] ?? 'operador';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-calendar-check me-2" style="color: #137fec;"></i>Gestión de Reservas</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Reserva creada exitosamente.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Reservas</span>
            <a href="/Sistema_RentACar/index.php?controller=Reservas&action=nueva" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Reserva
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
                            <th>Recogida</th>
                            <th>Entrega</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $r): ?>
                        <tr>
                            <td><?= $r['id_reserva'] ?></td>
                            <td><?= htmlspecialchars($r['cliente_nombre'] . ' ' . $r['cliente_apellido']) ?></td>
                            <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['numero_placa'] . ')') ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha_recogida'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha_entrega'])) ?></td>
                            <td>$<?= number_format($r['precio_total'], 2) ?></td>
                            <td>
                                <span class="badge <?= match($r['estado']) {
                                    'pendiente' => 'badge-pending',
                                    'confirmada' => 'badge-active',
                                    'en_curso' => 'badge-active',
                                    'completada' => 'badge-inactive',
                                    'cancelada' => 'badge-inactive',
                                    default => ''
                                } ?>">
                                    <?= ucfirst($r['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/Sistema_RentACar/index.php?controller=Devolucion&action=index&reserva=<?= $r['id_reserva'] ?>" class="btn btn-sm btn-outline-primary" title="Registrar devolución">
                                    <i class="fas fa-undo-alt"></i>
                                </a>
                                <?php if ($rol === 'admin'): ?>
                                    <a href="/Sistema_RentACar/index.php?controller=Reservas&action=editar&id=<?= $r['id_reserva'] ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/Sistema_RentACar/index.php?controller=Reservas&action=cancelar&id=<?= $r['id_reserva'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Cancelar esta reserva?')" title="Cancelar">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (isset($r['id_contrato']) && $r['id_contrato']): ?>
                                    <a href="/Sistema_RentACar/index.php?controller=Contratos&action=index&id=<?= $r['id_contrato'] ?>" class="btn btn-sm btn-outline-info" title="Ver contrato">
                                        <i class="fas fa-file-contract"></i>
                                    </a>
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
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>