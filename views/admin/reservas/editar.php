<?php
$titulo = 'Editar Reserva';
$seccion = 'reservas';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-edit me-2" style="color: #137fec;"></i>Editar Reserva #<?= $reserva['id_reserva'] ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/Sistema_RentACar/index.php?controller=Reservas&action=actualizar" method="POST">
                <input type="hidden" name="id" value="<?= $reserva['id_reserva'] ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cliente *</label>
                        <select name="id_cliente" class="form-control" required>
                            <option value="">Seleccionar cliente</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_cliente'] ?>" <?= $c['id_cliente'] == $reserva['id_cliente'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido'] . ' - ' . $c['DUI']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vehículo *</label>
                        <select name="id_vehiculo" id="id_vehiculo" class="form-control" required>
                            <option value="">Seleccionar vehículo</option>
                            <?php foreach ($vehiculos as $v): ?>
                                <option value="<?= $v['id_vehiculo'] ?>" <?= $v['id_vehiculo'] == $reserva['id_vehiculo'] ? 'selected' : '' ?> data-precio="<?= $v['precio_dia'] ?>">
                                    <?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['numero_placa'] . ') - $' . $v['precio_dia'] . '/día') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de recogida *</label>
                        <input type="date" name="fecha_recogida" id="fecha_recogida" class="form-control" value="<?= $reserva['fecha_recogida'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de entrega *</label>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" value="<?= $reserva['fecha_entrega'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="pendiente" <?= $reserva['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="confirmada" <?= $reserva['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                            <option value="en_curso" <?= $reserva['estado'] == 'en_curso' ? 'selected' : '' ?>>En curso</option>
                            <option value="completada" <?= $reserva['estado'] == 'completada' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelada" <?= $reserva['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div id="disponibilidad" class="alert" style="display: none;"></div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="btnGuardar"><i class="fas fa-save me-2"></i>Actualizar Reserva</button>
                    <a href="/Sistema_RentACar/index.php?controller=Reservas&action=index" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vehiculo = document.getElementById('id_vehiculo');
    const fechaInicio = document.getElementById('fecha_recogida');
    const fechaFin = document.getElementById('fecha_entrega');
    const disponibilidad = document.getElementById('disponibilidad');
    const btnGuardar = document.getElementById('btnGuardar');
    const reservaId = <?= $reserva['id_reserva'] ?>;

    function verificarDisponibilidad() {
        if (!vehiculo.value || !fechaInicio.value || !fechaFin.value) {
            disponibilidad.style.display = 'none';
            btnGuardar.disabled = false;
            return;
        }

        fetch('/Sistema_RentACar/index.php?controller=Reservas&action=verificarDisponibilidad&id_vehiculo=' + vehiculo.value + '&fecha_inicio=' + fechaInicio.value + '&fecha_fin=' + fechaFin.value + '&excluir=' + reservaId)
            .then(r => r.json())
            .then(d => {
                if (d.disponible) {
                    disponibilidad.className = 'alert alert-success';
                    disponibilidad.innerHTML = '✓ Vehículo disponible en esas fechas';
                    btnGuardar.disabled = false;
                } else {
                    disponibilidad.className = 'alert alert-danger';
                    disponibilidad.innerHTML = '✗ El vehículo no está disponible en esas fechas';
                    btnGuardar.disabled = true;
                }
                disponibilidad.style.display = 'block';
            });
    }

    vehiculo.addEventListener('change', verificarDisponibilidad);
    fechaInicio.addEventListener('change', verificarDisponibilidad);
    fechaFin.addEventListener('change', verificarDisponibilidad);
});
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>