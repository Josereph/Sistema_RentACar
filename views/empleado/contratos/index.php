<?php
$titulo = 'Gestión de Contratos';
$seccion = 'contratos';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_navbar.php';

$contratos = $contratos ?? [];
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-file-contract me-2" style="color: #137fec;"></i>Gestión de Contratos</h2>
        </div>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'pdf_generado'): ?>
        <div class="alert alert-success">PDF generado exitosamente.</div>
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
                            <th>Depósito</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contratos as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['numero_contrato']) ?></td>
                            <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td>
                            <td><?= htmlspecialchars($c['marca'] . ' ' . $c['modelo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_contrato'])) ?></td>
                            <td>$<?= number_format($c['deposito'], 2) ?></td>
                            <td>
                                <?php if (!empty($c['pdf_path'])): ?>
                                    <a href="<?= url('index.php?area=empleado&controller=ContratosEmpleado&action=verPdf&id=' . $c['id_contrato']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-pdf"></i> Ver PDF
                                    </a>
                                <?php else: ?>
                                    <a href="<?= url('index.php?area=empleado&controller=ContratosEmpleado&action=generarPdf&id=' . $c['id_contrato']) ?>" class="btn btn-sm btn-success">
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
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_footer.php'; ?>