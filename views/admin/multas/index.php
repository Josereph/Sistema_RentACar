<?php
$titulo = 'Gestión de Multas';
$seccion = 'multas';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-exclamation-triangle me-2" style="color: #137fec;"></i>Gestión de Multas</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Multa marcada como pagada.</div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3" id="multasTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">Pendientes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pagadas-tab" data-bs-toggle="tab" data-bs-target="#pagadas" type="button" role="tab">Pagadas</button>
        </li>
    </ul>

    <div class="tab-content" id="multasTabContent">
        <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
            <div class="card">
                <div class="card-header">Multas Pendientes</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Motivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($multasPendientes as $m): ?>
                                <tr>
                                    <td><?= $m['id_multa'] ?></td>
                                    <td><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></td>
                                    <td><?= htmlspecialchars($m['marca'] . ' ' . $m['modelo'] . ' (' . $m['numero_placa'] . ')') ?></td>
                                    <td><?= ucfirst($m['tipo']) ?></td>
                                    <td class="text-danger fw-bold">$<?= number_format($m['monto'], 2) ?></td>
                                    <td><?= htmlspecialchars($m['motivo']) ?></td>
                                    <td>
                                        <a href="/Sistema_RentACar/index.php?controller=Multas&action=pagar&id=<?= $m['id_multa'] ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como pagada?')">
                                            <i class="fas fa-check"></i> Pagar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pagadas" role="tabpanel">
            <div class="card">
                <div class="card-header">Multas Pagadas</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Vehículo</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($multasPagadas as $m): ?>
                                <tr>
                                    <td><?= $m['id_multa'] ?></td>
                                    <td><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></td>
                                    <td><?= htmlspecialchars($m['marca'] . ' ' . $m['modelo'] . ' (' . $m['numero_placa'] . ')') ?></td>
                                    <td><?= ucfirst($m['tipo']) ?></td>
                                    <td>$<?= number_format($m['monto'], 2) ?></td>
                                    <td><?= htmlspecialchars($m['motivo']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>