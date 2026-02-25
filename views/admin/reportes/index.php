<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: ' . url('index.php?controller=Auth&action=login'));
    exit;
}
$titulo = 'Reportes';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-chart-bar me-2" style="color: #137fec;"></i>Reportes Estadísticos</h2>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Vehículos más rentados</div>
                <div class="card-body">
                    <canvas id="chart1" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Ingresos por mes</div>
                <div class="card-body">
                    <canvas id="chart2" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Aquí puedes agregar gráficos reales con datos de la BD
</script>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>