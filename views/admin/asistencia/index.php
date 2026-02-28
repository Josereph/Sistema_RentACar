<?php
$titulo = 'Registro de Asistencia';
$seccion = 'asistencia';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-clock me-2" style="color: #137fec;"></i>Registro de Asistencia</h2>
        </div>
    </div>

    <!-- Filtro por fecha y botón para escanear -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="controller" value="AsistenciaAdmin">
                        <input type="hidden" name="action" value="index">
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($_GET['fecha'] ?? date('Y-m-d')) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="/Sistema_RentACar/index.php?controller=AsistenciaAdmin&action=escanear" class="btn btn-success">
                        <i class="fas fa-camera"></i> Tomar Asistencia (Cámara)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-2"></i>Asistencias del día
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha/Hora</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($asistencias)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No hay registros para esta fecha</td></tr>
                        <?php else: ?>
                            <?php foreach ($asistencias as $a): ?>
                            <tr>
                                <td><?= $a['id_asistencia'] ?></td>
                                <td><?= htmlspecialchars($a['nombre']) ?></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($a['fecha_hora'])) ?></td>
                                <td>
                                    <span class="badge <?= $a['tipo'] == 'entrada' ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= ucfirst($a['tipo']) ?>
                                    </span>
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
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>