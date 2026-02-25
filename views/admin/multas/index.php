<?php
$titulo = 'Multas Pendientes';
$seccion = 'multas';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-exclamation-triangle me-2" style="color: #137fec;"></i>Multas Pendientes</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Tipo</th>
                            <th>Motivo</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($multas)): ?>
                            <tr><td colspan="8" class="text-center text-muted">No hay multas pendientes</td></tr>
                        <?php else: ?>
                            <?php foreach ($multas as $m): ?>
                            <tr>
                                <td><?= $m['id_multa'] ?></td>
                                <td><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></td>
                                <td><?= htmlspecialchars($m['marca'] . ' ' . $m['modelo'] . ' (' . $m['numero_placa'] . ')') ?></td>
                                <td><?= ucfirst($m['tipo']) ?></td>
                                <td><?= htmlspecialchars($m['motivo']) ?></td>
                                <td class="text-danger fw-bold">$<?= number_format($m['monto'], 2) ?></td>
                                <td><?= date('d/m/Y', strtotime($m['created_at'])) ?></td>
                                <td>
                                    <a href="/Sistema_RentACar/index.php?controller=Multas&action=pagar&id=<?= $m['id_multa'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar esta multa como pagada?')">
                                        <i class="fas fa-check"></i> Pagada
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
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>