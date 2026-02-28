<?php
$titulo = 'Reportes Estadísticos';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

// Valores por defecto para evitar errores
$reservasHoy = $reservasHoy ?? 0;
$ingresosHoy = $ingresosHoy ?? 0;
$tasaCancelacion = $tasaCancelacion ?? 0;
$utilizacion = $utilizacion ?? 0;
$flotaDisponible = $flotaDisponible ?? 0;
$flotaRentada = $flotaRentada ?? 0;
$flotaMantenimiento = $flotaMantenimiento ?? 0;
$ticketPromedio = $ticketPromedio ?? 0;
$topVehiculosRentados = $topVehiculosRentados ?? [];
$topVehiculosIngresos = $topVehiculosIngresos ?? [];
$topClientesGasto = $topClientesGasto ?? [];
$proximasEntregas = $proximasEntregas ?? [];
$reservasPorEstado = $reservasPorEstado ?? [];
$ingresosPorConcepto = $ingresosPorConcepto ?? [];
?>
<div class="container-fluid mt-4">
    <!-- Título -->
    <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2><i class="fas fa-chart-bar me-2" style="color: #137fec;"></i>Reportes y Estadísticas</h2>
        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=avanzado" class="btn btn-primary">
            <i class="fas fa-chart-pie me-2"></i>Reportes Avanzados
        </a>
    </div>
</div>

    <!-- KPIs rápidos -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number"><?= number_format($reservasHoy) ?></div>
                <div class="stat-label">Reservas Hoy</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-number">$<?= number_format($ingresosHoy, 2) ?></div>
                <div class="stat-label">Ingresos Hoy</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-chart-line"></i></div>
                <div class="stat-number"><?= $tasaCancelacion ?>%</div>
                <div class="stat-label">Cancelación</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-percent"></i></div>
                <div class="stat-number"><?= $utilizacion ?>%</div>
                <div class="stat-label">Utilización Flota</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                <div class="stat-number"><?= number_format($flotaDisponible) ?></div>
                <div class="stat-label">Disponibles</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-key"></i></div>
                <div class="stat-number"><?= number_format($flotaRentada) ?></div>
                <div class="stat-label">En Renta</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-wrench"></i></div>
                <div class="stat-number"><?= number_format($flotaMantenimiento) ?></div>
                <div class="stat-label">Mantenimiento</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-number">$<?= number_format($ticketPromedio, 2) ?></div>
                <div class="stat-label">Ticket Promedio</div>
            </div>
        </div>
    </div>

    <!-- Tablas de datos -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">Reservas por estado</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <?php foreach ($reservasPorEstado as $r): ?>
                        <tr><td><?= ucfirst($r['estado']) ?></td><td><?= $r['total'] ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">Ingresos por concepto</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <?php foreach ($ingresosPorConcepto as $c): ?>
                        <tr><td><?= ucfirst($c['concepto']) ?></td><td>$<?= number_format($c['total'], 2) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">Top 5 vehículos más rentados</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <?php foreach ($topVehiculosRentados as $v): ?>
                        <tr><td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo']) ?></td><td><?= $v['total_reservas'] ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Top 5 vehículos por ingreso</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <?php foreach ($topVehiculosIngresos as $v): ?>
                        <tr><td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo']) ?></td><td>$<?= number_format($v['total_ingresos'], 2) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">Top 5 clientes por gasto</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <?php foreach ($topClientesGasto as $c): ?>
                        <tr><td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td><td>$<?= number_format($c['total_gastado'], 2) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximas entregas -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">Próximas entregas (3 días)</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Cliente</th><th>Vehículo</th><th>Fecha</th></tr></thead>
                        <tbody>
                            <?php foreach ($proximasEntregas as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></td>
                                <td><?= htmlspecialchars($e['marca'] . ' ' . $e['modelo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($e['fecha_entrega'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>