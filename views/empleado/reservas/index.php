<?php
$titulo = 'Reservas';
$seccion = 'reservas';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-calendar-check me-2" style="color: #137fec;"></i>Reservas</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Reserva creada exitosamente.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Lista de Reservas</span>
            <a href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=nueva') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Reserva
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Recogida</th>
                            <th>Entrega</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $r): ?>
                        <tr>
                            <td><?= $r['id_reserva'] ?></td>
                            <td><?= htmlspecialchars($r['cliente_nombre'] . ' ' . $r['cliente_apellido']) ?></td>
                            <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['numero_placa'] . ')') ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha_recogida'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha_entrega'])) ?></td>
                            <td>$<?= number_format($r['precio_total'], 2) ?></td>
                            <td>
                                <span class="badge <?= match($r['estado']) {
                                    'pendiente' => 'badge-pending',
                                    'confirmada' => 'badge-active',
                                    'en_curso' => 'badge-active',
                                    'completada' => 'badge-inactive',
                                    'cancelada' => 'badge-inactive',
                                    default => ''
                                } ?>">
                                    <?= ucfirst($r['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" onclick="verReserva(<?= $r['id_reserva'] ?>)" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (isset($r['numero_contrato'])): ?>
                                    <a href="/Sistema_RentACar/index.php?controller=Contratos&action=verPdf&id=<?= $r['id_contrato'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver contrato">
                                        <i class="fas fa-file-pdf"></i>
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

<!-- Modal para ver detalles de reserva -->
<div class="modal fade" id="modalVerReserva" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleReserva">
                Cargando...
            </div>
        </div>
    </div>
</div>

<script>
function verReserva(id) {
    fetch('<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=ver&id=') ?>' + id)
        .then(r => r.json())
        .then(d => {
            let html = `
                <p><strong>Reserva ID:</strong> ${d.id_reserva}</p>
                <p><strong>Cliente:</strong> ${d.nombre} ${d.apellido}</p>
                <p><strong>Email:</strong> ${d.correo}</p>
                <p><strong>Teléfono:</strong> ${d.telefono || 'N/A'}</p>
                <p><strong>Vehículo:</strong> ${d.marca} ${d.modelo} (${d.numero_placa})</p>
                <p><strong>Precio por día:</strong> $${d.precio_dia}</p>
                <p><strong>Fecha recogida:</strong> ${d.fecha_recogida}</p>
                <p><strong>Fecha entrega:</strong> ${d.fecha_entrega}</p>
                <p><strong>Total:</strong> $${d.precio_total}</p>
                <p><strong>Estado:</strong> ${d.estado}</p>
                <p><strong>Número de contrato:</strong> ${d.numero_contrato || 'N/A'}</p>
                <p><strong>Depósito:</strong> $${d.deposito || 0}</p>
            `;
            document.getElementById('detalleReserva').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalVerReserva')).show();
        });
}
</script>

<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_footer.php'; ?>