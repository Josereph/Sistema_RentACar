<?php
$titulo = 'Gestión de Contratos';
$seccion = 'contratos';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-file-contract me-2" style="color: #137fec;"></i>Gestión de Contratos</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-2"></i>Lista de Contratos
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>N° Contrato</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Fecha contrato</th>
                            <th>Recogida</th>
                            <th>Entrega</th>
                            <th>Depósito</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contratos as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['numero_contrato']) ?></td>
                            <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?><br>
                                <small>DUI: <?= htmlspecialchars($c['DUI']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($c['marca'] . ' ' . $c['modelo']) ?><br>
                                <small><?= htmlspecialchars($c['numero_placa']) ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_contrato'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_recogida'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_entrega'])) ?></td>
                            <td>$<?= number_format($c['deposito'], 2) ?></td>
                            <td>
                                <?php if ($c['pdf_path']): ?>
                                    <a href="<?= url('index.php?controller=Contratos&action=verPdf&id=' . $c['id_contrato']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Ver
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Sin PDF</span>
                                <?php endif; ?>
                            </td>
                            <td>
    <?php if ($c['pdf_path']): ?>
        <a href="/Sistema_RentACar/<?= $c['pdf_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver PDF">
            <i class="fas fa-file-pdf"></i>
        </a>
    <?php else: ?>
        <a href="/Sistema_RentACar/index.php?controller=Contratos&action=generarPdf&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-outline-success" title="Generar PDF">
            <i class="fas fa-file-pdf"></i> Generar
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