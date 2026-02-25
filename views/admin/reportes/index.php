<?php
$titulo = 'Reportes Estadísticos';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-chart-bar me-2" style="color: #137fec;"></i>Reportes Estadísticos</h2>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-number">$<?= number_format($multasPendientes ?? 0, 2) ?></div>
                <div class="stat-label">Multas Pendientes</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number">$<?= number_format($multasPagadas ?? 0, 2) ?></div>
                <div class="stat-label">Multas Pagadas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon cyan"><i class="fas fa-percent"></i></div>
                <div class="stat-number"><?= $porcentajeOcupacion ?? 0 ?>%</div>
                <div class="stat-label">Ocupación (último mes)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-tachometer-alt"></i></div>
                <div class="stat-number"><?= count($vehiculosMasRentados ?? []) ?></div>
                <div class="stat-label">Top Vehículos</div>
            </div>
        </div>
    </div>

    <!-- Gráficos y tablas -->
    <div class="row">
        <!-- Vehículos más rentados -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-trophy me-2"></i>Vehículos más rentados (top 10)</span>
                    <div>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=vehiculos_mas_rentados" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarPdf&tipo=vehiculos_mas_rentados" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($vehiculosMasRentados)): ?>
                        <p class="text-muted text-center">No hay datos suficientes para mostrar el gráfico.</p>
                    <?php else: ?>
                        <div style="width: 100%; height: 300px; position: relative;">
                            <canvas id="chartVehiculos" style="width: 100%; height: 100%;"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ingresos por mes -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line me-2"></i>Ingresos por mes (últimos 12 meses)</span>
                    <div>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=ingresos_mensuales" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Excel</a>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarPdf&tipo=ingresos_mensuales" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($ingresosPorMes)): ?>
                        <p class="text-muted text-center">No hay datos suficientes para mostrar el gráfico.</p>
                    <?php else: ?>
                        <div style="width: 100%; height: 300px; position: relative;">
                            <canvas id="chartIngresos" style="width: 100%; height: 100%;"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Top clientes -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-users me-2"></i>Clientes con más reservas</span>
                    <a href="/Sistema_RentACar/index.php?controller=Reportes&action=exportarExcel&tipo=top_clientes" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
                <div class="card-body">
                    <?php if (empty($topClientes)): ?>
                        <p class="text-muted text-center">No hay datos de clientes.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table data-table">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Email</th>
                                        <th>Total Reservas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topClientes as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td>
                                        <td><?= htmlspecialchars($c['correo']) ?></td>
                                        <td><?= $c['total_reservas'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño retraso para asegurar que el DOM esté listo y evitar loops
    setTimeout(function() {
        // Gráfico de vehículos más rentados
        <?php if (!empty($vehiculosMasRentados)): ?>
        var ctxVehiculos = document.getElementById('chartVehiculos');
        if (ctxVehiculos) {
            new Chart(ctxVehiculos.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_map(function($v) { return $v['marca'] . ' ' . $v['modelo']; }, $vehiculosMasRentados)) ?>,
                    datasets: [{
                        label: 'N° de reservas',
                        data: <?= json_encode(array_column($vehiculosMasRentados, 'total_reservas')) ?>,
                        backgroundColor: '#137fec'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        <?php endif; ?>

        // Gráfico de ingresos
        <?php if (!empty($ingresosPorMes)): ?>
        var ctxIngresos = document.getElementById('chartIngresos');
        if (ctxIngresos) {
            new Chart(ctxIngresos.getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($ingresosPorMes, 'mes')) ?>,
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: <?= json_encode(array_column($ingresosPorMes, 'total')) ?>,
                        borderColor: '#137fec',
                        backgroundColor: 'rgba(19,127,236,0.1)',
                        tension: 0.3,
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
        }
        <?php endif; ?>
    }, 100);
});
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>