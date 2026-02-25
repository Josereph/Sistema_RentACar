<?php
$titulo = 'Registrar Devolución';
$seccion = 'devoluciones';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-undo-alt me-2" style="color: #137fec;"></i>Registrar Devolución</h2>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Devolución registrada exitosamente.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error al registrar devolución: <?= htmlspecialchars($_GET['msg'] ?? '') ?></div>
    <?php endif; ?>

    <form action="/Sistema_RentACar/index.php?controller=Devolucion&action=registrar" method="POST" id="formDevolucion">
        <input type="hidden" name="id_reserva" id="id_reserva">

        <div class="row g-3">
            <!-- COL IZQUIERDA -->
            <div class="col-lg-7">
                <!-- Información de la renta -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-file-invoice me-2"></i> Información de la Renta
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° de Contrato *</label>
                                <input class="form-control" type="text" name="no_contrato" id="no_contrato"
                                       placeholder="RENT-2024-0001" required
                                       oninput="buscarContrato(this.value)">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehículo</label>
                                <input class="form-control" type="text" id="dev_vehiculo" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cliente</label>
                                <input class="form-control" type="text" id="dev_cliente" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Placa</label>
                                <input class="form-control" type="text" id="dev_placa" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Salida</label>
                                <input class="form-control" type="date" id="dev_fecha_salida" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Devolución Pactada</label>
                                <input class="form-control" type="date" id="dev_fecha_pactada" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Condición al retorno -->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-car-burst me-2"></i> Condición al Retorno
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha Real de Devolución *</label>
                                <input class="form-control" type="datetime-local" name="fecha_devolucion_real"
                                       id="fecha_devolucion_real" required value="<?= date('Y-m-d\TH:i') ?>"
                                       onchange="calcularCargos()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">KM al Retorno *</label>
                                <input class="form-control" type="number" name="km_retorno" id="km_retorno"
                                       placeholder="00000" required oninput="calcularKM()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nivel de Combustible Retorno *</label>
                                <select class="form-control" name="combustible_retorno" id="combustible_retorno" required onchange="calcularCargos()">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="lleno">Tanque lleno</option>
                                    <option value="tres_cuartos">¾ Tanque</option>
                                    <option value="medio">½ Tanque</option>
                                    <option value="cuarto">¼ Tanque</option>
                                    <option value="vacio">Casi vacío</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Daños observados</label>
                            <textarea class="form-control" name="danos_observados" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado General</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="estado_general" value="excelente"> Excelente
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="estado_general" value="bueno" checked> Bueno
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="estado_general" value="regular"> Regular
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="estado_general" value="malo"> Con daños
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COL DERECHA -->
            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-calculator me-2"></i> Resumen de Cobros
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Días rentados</span>
                                <span id="res_dias">-- días</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tarifa base</span>
                                <span id="res_tarifa">$0.00 / día</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">KM recorridos</span>
                                <span id="res_km">-- km</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Cargo por atraso</span>
                                <span id="res_atraso" class="text-danger">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Cargo combustible</span>
                                <span id="res_comb" class="text-warning">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Cargo por daños</span>
                                <input type="number" class="form-control form-control-sm w-50" name="cargo_danos" id="cargo_danos"
                                       placeholder="$0.00" step="0.01" min="0" value="0" oninput="calcularTotal()">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">TOTAL A COBRAR</span>
                                <span id="res_total" class="text-primary fw-bold fs-4">$0.00</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Método de Pago (para multas)</label>
                            <select class="form-control" name="metodo_pago">
                                <option value="">-- No aplicar --</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                            <small class="text-muted">Si se generan multas, se puede registrar el pago aquí.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observaciones finales</label>
                            <textarea class="form-control" name="observaciones_finales" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-fill" onclick="window.location.href='/Sistema_RentACar/index.php?controller=Devolucion&action=index'">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-check-circle"></i> Registrar Devolución
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let tarifaBase = 0;
let kmSalida = 0;
let fechaPactada = null;

function buscarContrato(no) {
    if (no.length < 5) return;
    fetch('/Sistema_RentACar/index.php?controller=Devolucion&action=buscarContrato&no=' + encodeURIComponent(no))
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                document.getElementById('dev_vehiculo').value = d.vehiculo;
                document.getElementById('dev_cliente').value = d.cliente;
                document.getElementById('dev_placa').value = d.placa;
                document.getElementById('dev_fecha_salida').value = d.fecha_salida;
                document.getElementById('dev_fecha_pactada').value = d.fecha_pactada;
                document.getElementById('res_tarifa').textContent = '$' + d.precio_dia + ' / día';
                document.getElementById('id_reserva').value = d.id_reserva;
                tarifaBase = parseFloat(d.precio_dia);
                fechaPactada = new Date(d.fecha_pactada + 'T00:00:00');
                // Suponemos km de salida como 0 (idealmente vendría de la reserva)
                kmSalida = 0;
                calcularCargos();
            } else {
                alert('Contrato no encontrado o no vigente');
            }
        });
}

function calcularCargos() {
    const fechaSalida = new Date(document.getElementById('dev_fecha_salida').value + 'T00:00:00');
    const fechaRetorno = new Date(document.getElementById('fecha_devolucion_real').value);
    if (!fechaPactada || !fechaRetorno.getTime() || !fechaSalida.getTime()) return;

    // Días rentados (incluye fracciones? usamos días completos)
    const dias = Math.ceil((fechaRetorno - fechaSalida) / (1000 * 60 * 60 * 24));
    document.getElementById('res_dias').textContent = dias + ' día(s)';

    // Atraso
    let atrasoDs = 0;
    if (fechaRetorno > fechaPactada) {
        atrasoDs = Math.ceil((fechaRetorno - fechaPactada) / (1000 * 60 * 60 * 24));
    }
    const cargoAtraso = atrasoDs * tarifaBase * 0.5;
    document.getElementById('res_atraso').textContent = '$' + cargoAtraso.toFixed(2);

    // Cargo combustible (asumiendo que salió lleno)
    const combustible = document.getElementById('combustible_retorno').value;
    const costos = { 'lleno': 0, 'tres_cuartos': 10, 'medio': 20, 'cuarto': 30, 'vacio': 50 };
    const cargoComb = costos[combustible] || 0;
    document.getElementById('res_comb').textContent = '$' + cargoComb.toFixed(2);

    calcularTotal();
}

function calcularKM() {
    const km = parseInt(document.getElementById('km_retorno').value || 0);
    document.getElementById('res_km').textContent = (km - kmSalida) + ' km recorridos';
}

function calcularTotal() {
    const atraso = parseFloat(document.getElementById('res_atraso').textContent.replace('$','')) || 0;
    const comb = parseFloat(document.getElementById('res_comb').textContent.replace('$','')) || 0;
    const danos = parseFloat(document.getElementById('cargo_danos').value) || 0;
    const base = tarifaBase * (parseInt(document.getElementById('res_dias').textContent) || 0);
    const total = base + atraso + comb + danos;
    document.getElementById('res_total').textContent = '$' + total.toFixed(2);
}
</script>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>