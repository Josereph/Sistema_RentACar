<?php
$titulo = 'Resultados del Reporte';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-chart-bar me-2" style="color: #137fec;"></i><?= htmlspecialchars($tituloReporte) ?></h2>
            <a href="/Sistema_RentACar/index.php?controller=ReportesAvanzados&action=index" class="btn btn-outline-secondary">Nuevo reporte</a>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <p><strong>Período:</strong> <?= date('d/m/Y', strtotime($fecha_inicio)) ?> al <?= date('d/m/Y', strtotime($fecha_fin)) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <?php
                            // Cabeceras según tipo
                            switch ($tipo):
                                case 'reservas': ?>
                                    <th>ID</th><th>Fecha</th><th>Cliente</th><th>Vehículo</th><th>Recogida</th><th>Entrega</th><th>Total</th><th>Estado</th>
                                    <?php break;
                                case 'ingresos_concepto': ?>
                                    <th>Concepto</th><th>Cantidad</th><th>Total</th>
                                    <?php break;
                                case 'top_vehiculos': ?>
                                    <th>Vehículo</th><th>Placa</th><th>Tipo</th><th>Reservas</th><th>Ingresos</th>
                                    <?php break;
                                case 'top_clientes': ?>
                                    <th>Cliente</th><th>Email</th><th>Reservas</th><th>Gasto</th>
                                    <?php break;
                                case 'multas': ?>
                                    <th>ID</th><th>Fecha</th><th>Cliente</th><th>Vehículo</th><th>Tipo</th><th>Motivo</th><th>Monto</th><th>Pagada</th>
                                    <?php break;
                                case 'checklist_fallas': ?>
                                    <th>Ítem</th><th>Total fallas</th>
                                    <?php break;
                                case 'mantenimientos': ?>
                                    <th>Vehículo</th><th>Tipo</th><th>Descripción</th><th>Inicio</th><th>Fin</th><th>Costo</th><th>KM</th><th>Estado</th>
                                    <?php break;
                                case 'devoluciones_tardias': ?>
                                    <th>Cliente</th><th>Vehículo</th><th>Fecha programada</th><th>Fecha real</th><th>Días atraso</th>
                                    <?php break;
                                case 'utilizacion_flota': ?>
                                    <th>Fecha</th><th>Vehículos rentados</th><th>Total flota</th><th>% Ocupación</th>
                                    <?php break;
                            endswitch;
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($resultados)): ?>
                            <tr><td colspan="10" class="text-center text-muted">No hay datos para el período seleccionado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($resultados as $row): ?>
                                <tr>
                                    <?php
                                    switch ($tipo):
                                        case 'reservas': ?>
                                            <td><?= $row['id_reserva'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_reserva'])) ?></td>
                                            <td><?= htmlspecialchars($row['cliente_nombre'] . ' ' . $row['cliente_apellido']) ?></td>
                                            <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_recogida'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_entrega'])) ?></td>
                                            <td>$<?= number_format($row['precio_total'], 2) ?></td>
                                            <td><?= ucfirst($row['estado']) ?></td>
                                            <?php break;
                                        case 'ingresos_concepto': ?>
                                            <td><?= ucfirst($row['concepto']) ?></td>
                                            <td><?= $row['cantidad'] ?></td>
                                            <td>$<?= number_format($row['total'], 2) ?></td>
                                            <?php break;
                                        case 'top_vehiculos': ?>
                                            <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo']) ?></td>
                                            <td><?= htmlspecialchars($row['numero_placa']) ?></td>
                                            <td><?= $row['tipo_vehiculo'] ?></td>
                                            <td><?= $row['total_reservas'] ?></td>
                                            <td>$<?= number_format($row['total_ingresos'], 2) ?></td>
                                            <?php break;
                                        case 'top_clientes': ?>
                                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                                            <td><?= htmlspecialchars($row['correo']) ?></td>
                                            <td><?= $row['total_reservas'] ?></td>
                                            <td>$<?= number_format($row['total_gastado'], 2) ?></td>
                                            <?php break;
                                        case 'multas': ?>
                                            <td><?= $row['id_multa'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                                            <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') ?></td>
                                            <td><?= ucfirst($row['tipo']) ?></td>
                                            <td><?= htmlspecialchars($row['motivo']) ?></td>
                                            <td>$<?= number_format($row['monto'], 2) ?></td>
                                            <td><?= $row['pagada'] ? 'Sí' : 'No' ?></td>
                                            <?php break;
                                        case 'checklist_fallas': ?>
                                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                                            <td><?= $row['total_fallas'] ?></td>
                                            <?php break;
                                        case 'mantenimientos': ?>
                                            <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') ?></td>
                                            <td><?= ucfirst($row['tipo']) ?></td>
                                            <td><?= htmlspecialchars($row['descripcion']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_inicio'])) ?></td>
                                            <td><?= $row['fecha_fin'] ? date('d/m/Y', strtotime($row['fecha_fin'])) : '-' ?></td>
                                            <td>$<?= number_format($row['costo'], 2) ?></td>
                                            <td><?= $row['km_actual'] ?? '-' ?></td>
                                            <td><?= ucfirst($row['estado']) ?></td>
                                            <?php break;
                                        case 'devoluciones_tardias': ?>
                                            <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                                            <td><?= htmlspecialchars($row['marca'] . ' ' . $row['modelo']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_entrega'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha_devolucion_real'])) ?></td>
                                            <td><?= $row['dias_atraso'] ?> días</td>
                                            <?php break;
                                        case 'utilizacion_flota': ?>
                                            <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                            <td><?= $row['rentados'] ?></td>
                                            <td><?= $row['total'] ?></td>
                                            <td><?= $row['porcentaje'] ?>%</td>
                                            <?php break;
                                    endswitch;
                                    ?>
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