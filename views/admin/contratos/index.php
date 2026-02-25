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

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['success'] == 'pdf_generado'): ?>
                PDF generado exitosamente.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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
                                <?php if (!empty($c['pdf_path'])): ?>
                                    <!-- Botón azul para ver PDF existente -->
                                    <a href="/Sistema_RentACar/index.php?controller=Contratos&action=verPdf&id=<?= $c['id_contrato'] ?>" target="_blank" class="btn btn-sm btn-primary" title="Ver PDF">
                                        <i class="fas fa-file-pdf"></i> Ver PDF
                                    </a>
                                <?php else: ?>
                                    <!-- Botón verde para generar PDF -->
                                    <a href="/Sistema_RentACar/index.php?controller=Contratos&action=generarPdf&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-success" title="Generar PDF">
                                        <i class="fas fa-file-pdf"></i> Generar PDF
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