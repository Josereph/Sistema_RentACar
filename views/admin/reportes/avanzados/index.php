<?php
$titulo = 'Reportes Estadísticos';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

// Valores por defecto
$reservasHoy = $reservasHoy ?? 0;
$ingresosHoy = $ingresosHoy ?? 0;
$ingresosMes = $ingresosMes ?? 0;
$ingresosAnio = $ingresosAnio ?? 0;
$ingresosPorConcepto = $ingresosPorConcepto ?? [];
$ticketPromedio = $ticketPromedio ?? 0;
$tasaCancelacion = $tasaCancelacion ?? 0;
$tasaConversion = $tasaConversion ?? 0;
$tasaMorosidad = $tasaMorosidad ?? 0;
$flotaDisponible = $flotaDisponible ?? 0;
$flotaRentada = $flotaRentada ?? 0;
$flotaMantenimiento = $flotaMantenimiento ?? 0;
$utilizacion = $utilizacion ?? 0;
$topVehiculosRentados = $topVehiculosRentados ?? [];
$topVehiculosIngresos = $topVehiculosIngresos ?? [];
$topClientesGasto = $topClientesGasto ?? [];
$totalMultas = $totalMultas ?? 0;
$porcentajeMultasPagadas = $porcentajeMultasPagadas ?? 0;
$costoMantenimientoAnual = $costoMantenimientoAnual ?? 0;
$fallasChecklist = $fallasChecklist ?? 0;
$tiempoPromedioMantenimiento = $tiempoPromedioMantenimiento ?? 0;
$proximasEntregas = $proximasEntregas ?? [];
$reservasPorEstado = $reservasPorEstado ?? [];
$reservasPorMes = $reservasPorMes ?? [];
$ingresosPorMes = $ingresosPorMes ?? [];
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-chart-bar me-2" style="color: #137fec;"></i>Reportes y Estadísticas</h2>
            <p class="text-muted">Indicadores clave y análisis detallado del negocio.</p>
        </div>
    </div>

    <!-- Fila 1: KPIs generales -->
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

    <!-- Fila 2: Más KPIs -->
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

    <!-- Fila 3: Más KPIs (multas, mantenimiento, etc.) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number">$<?= number_format($totalMultas, 2) ?></div>
                <div class="stat-label">Total Multas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?= $porcentajeMultasPagadas ?>%</div>
                <div class="stat-label">Multas Pagadas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-wrench"></i></div>
                <div class="stat-number">$<?= number_format($costoMantenimientoAnual, 2) ?></div>
                <div class="stat-label">Costo Mant. (12m)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-number"><?= number_format($fallasChecklist) ?></div>
                <div class="stat-label">Fallas Checklist (12m)</div>
            </div>
        </div>
    </div>

    <!-- Secciones detalladas -->
    <div class="row">
        <!-- Finanzas -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-coins me-2"></i>Ingresos por concepto (último año)</span>
                    <div>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=ingresos_mensuales" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i></a>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarPdf&tipo=ingresos_mensuales" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Concepto</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ingresosPorConcepto as $c): ?>
                            <tr>
                                <td><?= ucfirst($c['concepto']) ?></td>
                                <td>$<?= number_format($c['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ingresos mensuales -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line me-2"></i>Ingresos mensuales (último año)</span>
                    <div>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=ingresos_mensuales" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i></a>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarPdf&tipo=ingresos_mensuales" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Mes</th><th>Ingresos</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ingresosPorMes as $i): ?>
                            <tr>
                                <td><?= $i['mes'] ?></td>
                                <td>$<?= number_format($i['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top vehículos rentados -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-trophy me-2"></i>Top 5 vehículos más rentados</span>
                    <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=vehiculos_mas_rentados" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i></a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Vehículo</th><th>Reservas</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topVehiculosRentados as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['numero_placa'] . ')') ?></td>
                                <td><?= $v['total_reservas'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top vehículos por ingreso -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-dollar-sign me-2"></i>Top 5 vehículos por ingreso</span>
                    <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=vehiculos_mas_rentados" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i></a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Vehículo</th><th>Ingresos</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topVehiculosIngresos as $v): ?>
                            <tr>
                                <td><?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['numero_placa'] . ')') ?></td>
                                <td>$<?= number_format($v['total_ingresos'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top clientes por gasto -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2"></i>Top 5 clientes por gasto</span>
                    <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=top_clientes" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i></a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Cliente</th><th>Gasto</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topClientesGasto as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td>
                                <td>$<?= number_format($c['total_gastado'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximas entregas -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock me-2"></i>Próximas entregas (próximos 3 días)
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr><th>Cliente</th><th>Vehículo</th><th>Fecha</th></tr>
                        </thead>
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

    <!-- Nota: Gráficos desactivados temporalmente para evitar bucles -->
    <!-- Si deseas reactivarlos, descomenta el script Chart.js y los canvas en el HTML -->
</div>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>