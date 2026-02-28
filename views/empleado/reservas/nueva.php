<?php
$titulo = 'Nueva Reserva';
$seccion = 'reservas';
include PROJECT_ROOT_FS . '/views/empleado/layouts/header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-plus-circle me-2" style="color: #137fec;"></i>Nueva Reserva</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=crear') ?>" method="POST" id="formReserva">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cliente *</label>
                        <select name="id_cliente" class="form-control" required>
                            <option value="">Seleccionar cliente</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido'] . ' - ' . $c['DUI']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vehículo *</label>
                        <select name="id_vehiculo" id="id_vehiculo" class="form-control" required>
                            <option value="">Seleccionar vehículo</option>
                            <?php foreach ($vehiculos as $v): ?>
                                <option value="<?= $v['id_vehiculo'] ?>" data-precio="<?= $v['precio_dia'] ?>">
                                    <?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['numero_placa'] . ') - $' . $v['precio_dia'] . '/día') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de recogida *</label>
                        <input type="date" name="fecha_recogida" id="fecha_recogida" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de entrega *</label>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Depósito (opcional)</label>
                        <input type="number" step="0.01" name="deposito" id="deposito" class="form-control" value="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Método de pago (si hay depósito)</label>
                        <select name="metodo_pago" class="form-control">
                            <option value="">-- Ninguno --</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div id="disponibilidad" class="alert" style="display: none;"></div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="btnGuardar"><i class="fas fa-save me-2"></i>Guardar Reserva</button>
                    <a href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=index') ?>" class="btn btn-outline-secondary">Cancelar</a>
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

    function verificarDisponibilidad() {
        if (!vehiculo.value || !fechaInicio.value || !fechaFin.value) {
            disponibilidad.style.display = 'none';
            btnGuardar.disabled = false;
            return;
        }

        fetch('<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=verificarDisponibilidad') ?>?id_vehiculo=' + vehiculo.value + '&fecha_inicio=' + fechaInicio.value + '&fecha_fin=' + fechaFin.value)
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

<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/footer.php'; ?>