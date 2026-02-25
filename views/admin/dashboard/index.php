<?php
$titulo = 'Panel de Control';
$seccion = 'dashboard';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>

<div class="container-fluid mt-4">
    <!-- Título -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-tachometer-alt me-2" style="color: #137fec;"></i>Panel de Control</h2>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?= $totalClientes ?></div>
                <div class="stat-label">Total Clientes</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-car"></i></div>
                <div class="stat-number"><?= $vehiculosDisponibles ?></div>
                <div class="stat-label">Vehículos Disponibles</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-key"></i></div>
                <div class="stat-number"><?= $reservasActivas ?></div>
                <div class="stat-label">Reservas Activas</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-number"><?= $devolucionesHoy ?></div>
                <div class="stat-label">Devoluciones Hoy</div>
            </div>
        </div>
    </div>

    <!-- Gráfico de reservas por mes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2"></i>Reservas por mes (últimos 6 meses)
                </div>
                <div class="card-body">
                    <canvas id="graficoReservas" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de dos columnas -->
    <div class="row">
        <!-- Columna izquierda: Próximas devoluciones y Alertas -->
        <div class="col-lg-6 mb-4">
            <!-- Próximas devoluciones -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clock me-2"></i>Próximas devoluciones (5 días)</span>
                    <a href="<?= url('index.php?controller=Devolucion&action=index') ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Placa</th>
                                    <th>Fecha</th>
                                    <th>Días</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($proximasDevoluciones)): ?>
                                    <tr><td colspan="6" class="text-center text-muted py-3">No hay devoluciones próximas</td></tr>
                                <?php else: ?>
                                    <?php foreach ($proximasDevoluciones as $dev): 
                                        $diasRestantes = (strtotime($dev['fecha_entrega']) - time()) / 86400;
                                        if ($diasRestantes < 0) $clase = 'text-danger fw-bold';
                                        elseif ($diasRestantes <= 2) $clase = 'text-warning fw-bold';
                                        else $clase = 'text-success';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($dev['nombre'] . ' ' . $dev['apellido']) ?></td>
                                        <td><?= htmlspecialchars($dev['marca'] . ' ' . $dev['modelo']) ?></td>
                                        <td><?= htmlspecialchars($dev['numero_placa']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($dev['fecha_entrega'])) ?></td>
                                        <td class="<?= $clase ?>"><?= round($diasRestantes) ?> días</td>
                                        <td>
                                            <a href="<?= url('index.php?controller=Devolucion&action=index&reserva=' . $dev['id_reserva']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alertas: Checklist pendientes -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Checklist pendientes (últimos 2 días)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Fecha devolución</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($checklistPendientes)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">Todos los checklist están al día</td></tr>
                                <?php else: ?>
                                    <?php foreach ($checklistPendientes as $ch): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($ch['nombre'] . ' ' . $ch['apellido']) ?></td>
                                        <td><?= htmlspecialchars($ch['marca'] . ' ' . $ch['modelo']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($ch['fecha_devolucion_real'])) ?></td>
                                        <td>
                                            <a href="<?= url('index.php?controller=Checklist&action=index&id_devolucion=' . $ch['id_devolucion']) ?>" class="btn btn-sm btn-warning">
                                                Realizar checklist
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Multas pendientes -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-dollar-sign me-2 text-danger"></i>Multas pendientes de pago
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Motivo</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($multasPendientes)): ?>
                                    <tr><td colspan="3" class="text-center text-muted py-3">No hay multas pendientes</td></tr>
                                <?php else: ?>
                                    <?php foreach ($multasPendientes as $m): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></td>
                                        <td><?= htmlspecialchars($m['motivo']) ?></td>
                                        <td class="text-danger fw-bold">$<?= number_format($m['monto'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Últimas reservas y vehículos en mantenimiento -->
        <div class="col-lg-6 mb-4">
            <!-- Últimas reservas -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-plus me-2"></i>Últimas reservas</span>
                    <a href="#" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ultimasReservas)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-3">No hay reservas recientes</td></tr>
                                <?php else: ?>
                                    <?php foreach ($ultimasReservas as $res): 
                                        $claseEstado = match($res['estado']) {
                                            'en_curso' => 'badge-active',
                                            'completada' => 'badge-inactive',
                                            'pendiente' => 'badge-pending',
                                            default => ''
                                        };
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($res['nombre'] . ' ' . $res['apellido']) ?></td>
                                        <td><?= htmlspecialchars($res['marca'] . ' ' . $res['modelo']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($res['fecha_reserva'])) ?></td>
                                        <td><span class="badge <?= $claseEstado ?>"><?= ucfirst($res['estado']) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Vehículos en mantenimiento -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-wrench me-2"></i>Vehículos en mantenimiento</span>
                    <a href="<?= url('index.php?controller=Vehiculos&action=calendario') ?>" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Vehículo</th>
                                    <th>Placa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($vehiculosMantenimiento)): ?>
                                    <tr><td colspan="2" class="text-center text-muted py-3">No hay vehículos en mantenimiento</td></tr>
                                <?php else: ?>
                                    <?php foreach ($vehiculosMantenimiento as $v): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo']) ?></td>
                                        <td><?= htmlspecialchars($v['numero_placa']) ?></td>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de reservas por mes
    const ctx = document.getElementById('graficoReservas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($meses) ?>,
            datasets: [{
                label: 'Reservas',
                data: <?= json_encode($totales) ?>,
                borderColor: '#137fec',
                backgroundColor: 'rgba(19,127,236,0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>