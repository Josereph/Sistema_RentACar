<?php
$titulo = 'Panel de Empleado';
$seccion = 'dashboard';
include PROJECT_ROOT_FS . '/views/empleado/layouts/header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-tachometer-alt me-2" style="color: #137fec;"></i>Panel de Empleado</h2>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?= number_format($totalClientes) ?></div>
                <div class="stat-label">Total Clientes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-car"></i></div>
                <div class="stat-number"><?= number_format($vehiculosDisponibles) ?></div>
                <div class="stat-label">Vehículos Disponibles</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-number"><?= number_format($reservasHoy) ?></div>
                <div class="stat-label">Reservas Hoy</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-number"><?= number_format($devolucionesPendientes) ?></div>
                <div class="stat-label">Devoluciones Próximas</div>
            </div>
        </div>
    </div>

    <!-- Reservas recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-history me-2"></i>Reservas Recientes</span>
                    <a href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=index') ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Recogida</th>
                                    <th>Entrega</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reservasRecientes)): ?>
                                    <tr><td colspan="6" class="text-center text-muted py-3">No hay reservas recientes</td></tr>
                                <?php else: ?>
                                    <?php foreach ($reservasRecientes as $r): ?>
                                    <tr>
                                        <td><?= $r['id_reserva'] ?></td>
                                        <td><?= htmlspecialchars($r['nombre'] . ' ' . $r['apellido']) ?></td>
                                        <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($r['fecha_recogida'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($r['fecha_entrega'])) ?></td>
                                        <td>
                                            <span class="badge <?= match($r['estado']) {
                                                'pendiente' => 'badge-pending',
                                                'confirmada' => 'badge-active',
                                                'en_curso' => 'badge-active',
                                                'completada' => 'badge-inactive',
                                                default => ''
                                            } ?>"><?= ucfirst($r['estado']) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos (opcional) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </div>
                <div class="card-body">
                    <a href="<?= url('index.php?area=empleado&controller=DevolucionEmpleado&action=index') ?>" class="btn btn-primary me-2 mb-2">
                        <i class="fas fa-undo-alt"></i> Registrar Devolución
                    </a>
                    <a href="<?= url('index.php?area=empleado&controller=ChecklistEmpleado&action=index') ?>" class="btn btn-success me-2 mb-2">
                        <i class="fas fa-clipboard-check"></i> Nuevo Checklist
                    </a>
                    <a href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=nueva') ?>" class="btn btn-warning me-2 mb-2">
                        <i class="fas fa-plus"></i> Crear Reserva
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/footer.php'; ?>