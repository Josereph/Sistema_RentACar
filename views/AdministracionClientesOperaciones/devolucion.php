<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Devolución de Vehículo | CarWash RentaCar</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- SIEMPRE con BASE_URL -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/AdministracionClientesOperaciones/clientes.css" />
</head>
<body>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">🚗</div>
    <span>CarWash<small>RentaCar</small></span>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-title">Principal</div>
    <a href="#"><i class="fas fa-gauge-high"></i> Dashboard</a>

    <div class="nav-section-title">Clientes</div>
    <a href="<?= $BASE_URL ?>/index.php?v=clientes"><i class="fas fa-users"></i> Gestión de Clientes</a>

    <div class="nav-section-title">Vehículos</div>
    <a href="<?= $BASE_URL ?>/index.php?v=disponibilidad"><i class="fas fa-calendar-days"></i> Calendario</a>
    <a href="<?= $BASE_URL ?>/index.php?v=devolucion" class="active"><i class="fas fa-rotate-left"></i> Devolución</a>
    <a href="<?= $BASE_URL ?>/index.php?v=checklist"><i class="fas fa-clipboard-check"></i> Checklist</a>

    <div class="nav-section-title">Sistema</div>
    <a href="#"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</a>
  </nav>
  <div class="sidebar-footer">v1.0.0 &copy; 2024 CarWash</div>
</aside>

<div class="main-content">
  <header class="topbar">
    <button class="btn-icon d-lg-none" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h1 class="topbar-title"><i class="fas fa-rotate-left me-2" style="color:var(--primary-light)"></i>Formulario de <span>Devolución</span></h1>
    <div class="topbar-actions">
      <button class="btn-icon"><i class="fas fa-bell"></i></button>
      <div class="user-chip"><div class="avatar">A</div>Admin</div>
    </div>
  </header>

  <div class="page-body">
    <form action="<?= $BASE_URL ?>/controller/AdministracionClientesOperaciones/DevolucionController.php"
          method="POST" enctype="multipart/form-data" id="formDevolucion">
      <input type="hidden" name="action" value="registrar_devolucion">

      <div class="row g-3">

        <!-- COL IZQUIERDA -->
        <div class="col-lg-7">

          <!-- INFO RENTA -->
          <div class="card fade-up">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-file-invoice"></i> Información de la Renta</div>
            </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">N° de Contrato *</label>
                  <input class="form-control" type="text" name="no_contrato" id="no_contrato"
                         placeholder="RENT-2024-0001" required
                         oninput="buscarContrato(this.value)">
                </div>
                <div class="form-group">
                  <label class="form-label">Vehículo</label>
                  <input class="form-control" type="text" name="vehiculo" id="dev_vehiculo"
                         placeholder="Auto-completado" readonly>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Cliente</label>
                  <input class="form-control" type="text" id="dev_cliente" placeholder="Auto-completado" readonly>
                  <input type="hidden" name="cliente_id" id="dev_cliente_id">
                </div>
                <div class="form-group">
                  <label class="form-label">Placa</label>
                  <input class="form-control" type="text" id="dev_placa" placeholder="P-0000" readonly>
                  <input type="hidden" name="vehiculo_id" id="dev_vehiculo_id">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Fecha de Salida</label>
                  <input class="form-control" type="date" id="dev_fecha_salida" name="fecha_salida" readonly>
                </div>
                <div class="form-group">
                  <label class="form-label">Fecha de Devolución Pactada</label>
                  <input class="form-control" type="date" id="dev_fecha_pactada" name="fecha_devolucion_pactada" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- CONDICION AL RETORNO -->
          <div class="card fade-up delay-1">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-car-burst"></i> Condición al Retorno</div>
            </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Fecha Real de Devolución *</label>
                  <input class="form-control" type="datetime-local" name="fecha_devolucion_real"
                         id="fecha_devolucion_real" required
                         value="<?= date('Y-m-d\TH:i') ?>"
                         onchange="calcularCargos()">
                </div>
                <div class="form-group">
                  <label class="form-label">KM al Retorno *</label>
                  <input class="form-control" type="number" name="km_retorno" id="km_retorno"
                         placeholder="00000" required
                         oninput="calcularKM()">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Nivel de Combustible Salida</label>
                  <select class="form-control" id="combustible_salida" readonly disabled>
                    <option>½ Tanque</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Nivel de Combustible Retorno *</label>
                  <select class="form-control" name="combustible_retorno" required>
                    <option value="">-- Seleccionar --</option>
                    <option value="lleno">Tanque lleno</option>
                    <option value="tres_cuartos">¾ Tanque</option>
                    <option value="medio">½ Tanque</option>
                    <option value="cuarto">¼ Tanque</option>
                    <option value="vacio">Casi vacío</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Daños observados</label>
                <textarea class="form-control" name="danos_observados" rows="3"
                          placeholder="Describa rayones, abolladuras, roturas u otros daños encontrados al momento de la devolución..."></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">Estado General</label>
                <div class="d-flex gap-2 flex-wrap">
                  <label style="cursor:pointer;display:flex;align-items:center;gap:8px;background:rgba(0,0,0,0.25);border:1px solid var(--card-border);border-radius:8px;padding:8px 16px;">
                    <input type="radio" name="estado_general" value="excelente"> <span style="color:var(--text-main)">Excelente</span>
                  </label>
                  <label style="cursor:pointer;display:flex;align-items:center;gap:8px;background:rgba(0,0,0,0.25);border:1px solid var(--card-border);border-radius:8px;padding:8px 16px;">
                    <input type="radio" name="estado_general" value="bueno" checked> <span style="color:var(--text-main)">Bueno</span>
                  </label>
                  <label style="cursor:pointer;display:flex;align-items:center;gap:8px;background:rgba(0,0,0,0.25);border:1px solid var(--card-border);border-radius:8px;padding:8px 16px;">
                    <input type="radio" name="estado_general" value="regular"> <span style="color:var(--text-main)">Regular</span>
                  </label>
                  <label style="cursor:pointer;display:flex;align-items:center;gap:8px;background:rgba(0,0,0,0.25);border:1px solid var(--card-border);border-radius:8px;padding:8px 16px;">
                    <input type="radio" name="estado_general" value="malo"> <span style="color:var(--text-main)">Con daños</span>
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Calificación del cliente</label>
                <div class="star-rating" data-target="rating_cliente" data-value="0">
                  <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <input type="hidden" name="rating_cliente" id="rating_cliente">
              </div>
            </div>
          </div>

          <!-- FOTOS -->
          <div class="card fade-up delay-2">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-camera"></i> Fotos de Devolución</div>
              <small style="color:var(--text-muted)">Máx. 6 fotos</small>
            </div>
            <div class="card-body">
              <div class="photo-upload-grid">
                <div class="photo-slot" data-input="foto1"><i class="fas fa-camera"></i><span>Frente</span></div>
                <div class="photo-slot" data-input="foto2"><i class="fas fa-camera"></i><span>Trasera</span></div>
                <div class="photo-slot" data-input="foto3"><i class="fas fa-camera"></i><span>Lado Izq.</span></div>
                <div class="photo-slot" data-input="foto4"><i class="fas fa-camera"></i><span>Lado Der.</span></div>
                <div class="photo-slot" data-input="foto5"><i class="fas fa-camera"></i><span>Interior</span></div>
                <div class="photo-slot" data-input="foto6"><i class="fas fa-camera"></i><span>Tablero</span></div>
              </div>
              <small style="color:var(--text-muted);font-size:.75rem;margin-top:8px;display:block">Click en cada recuadro para subir foto</small>
            </div>
          </div>

        </div><!-- /col izq -->

        <!-- COL DERECHA -->
        <div class="col-lg-5">

          <div class="card fade-up delay-1">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-calculator"></i> Resumen de Cobros</div>
            </div>
            <div class="card-body">
              <div style="background:rgba(0,0,0,0.3);border-radius:10px;padding:16px;margin-bottom:16px">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">Días rentados</span>
                  <span id="res_dias" style="color:var(--white);font-weight:600">-- días</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">Tarifa base</span>
                  <span id="res_tarifa" style="color:var(--white)">$0.00 / día</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">KM recorridos</span>
                  <span id="res_km" style="color:var(--white)">-- km</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">Cargo por atraso</span>
                  <span id="res_atraso" style="color:var(--danger)">$0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">Cargo combustible</span>
                  <span id="res_comb" style="color:var(--warning)">$0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:var(--text-muted);font-size:.85rem">Cargo por daños</span>
                  <input type="number" class="form-control" name="cargo_danos" id="cargo_danos"
                         placeholder="$0.00" step="0.01" min="0"
                         style="width:110px;padding:4px 8px;font-size:.85rem"
                         oninput="calcularTotal()">
                </div>
                <hr style="border-color:var(--card-border)">
                <div class="d-flex justify-content-between align-items-center">
                  <span style="color:var(--white);font-weight:700">TOTAL A COBRAR</span>
                  <span id="res_total" style="color:var(--primary-light);font-size:1.3rem;font-weight:700">$0.00</span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Método de Pago</label>
                <select class="form-control" name="metodo_pago" required>
                  <option value="">-- Seleccionar --</option>
                  <option value="efectivo">Efectivo</option>
                  <option value="tarjeta">Tarjeta de crédito/débito</option>
                  <option value="transferencia">Transferencia</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Observaciones finales</label>
                <textarea class="form-control" name="observaciones_finales" rows="3"
                          placeholder="Notas adicionales del proceso de devolución..."></textarea>
              </div>
            </div>
          </div>

          <div class="card fade-up delay-2">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-signature"></i> Firma del Cliente</div>
              <button type="button" class="btn btn-outline btn-sm" onclick="clearSignature('signatureCanvas')">
                <i class="fas fa-eraser"></i> Limpiar
              </button>
            </div>
            <div class="card-body p-2">
              <div class="signature-wrapper">
                <canvas id="signatureCanvas"></canvas>
              </div>
              <small style="color:var(--text-muted);font-size:.73rem;display:block;text-align:center;margin-top:6px">
                <i class="fas fa-pen-nib"></i> Firme con el dedo o ratón
              </small>
              <input type="hidden" name="firma_cliente" id="firma_cliente_data">
            </div>
          </div>

          <div class="d-flex gap-2 mt-2 fade-up delay-3">
            <!-- Mejor: volver a una vista conocida -->
            <button type="button" class="btn btn-outline flex-fill" onclick="window.location.href='<?= $BASE_URL ?>/index.php?v=clientes'">
              <i class="fas fa-arrow-left"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary flex-fill" onclick="capturarFirma()">
              <i class="fas fa-check-circle"></i> Registrar Devolución
            </button>
          </div>

        </div><!-- /col der -->
      </div><!-- /row -->
    </form>
  </div><!-- /page-body -->
</div><!-- /main-content -->

<div class="toast-container" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SIEMPRE con BASE_URL -->
<script src="<?= $BASE_URL ?>/assets/js/AdministracionClientesOperaciones/clientes.js"></script>

<script>
// ─ Lógica específica de devolución ──────────────────────────────────────────

function buscarContrato(no) {
  if (no.length < 5) return;

  // IMPORTANTE: ruta absoluta con BASE_URL
  const url = `<?= $BASE_URL ?>/controller/AdministracionClientesOperaciones/DevolucionController.php?action=buscar&no=${encodeURIComponent(no)}`;

  fetch(url)
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        document.getElementById('dev_vehiculo').value     = d.vehiculo;
        document.getElementById('dev_cliente').value     = d.cliente;
        document.getElementById('dev_placa').value       = d.placa;
        document.getElementById('dev_cliente_id').value  = d.cliente_id;
        document.getElementById('dev_vehiculo_id').value = d.vehiculo_id;
        document.getElementById('dev_fecha_salida').value  = d.fecha_salida;
        document.getElementById('dev_fecha_pactada').value = d.fecha_pactada;
        calcularCargos();
      }
    })
    .catch(() => {
      // Demo: llenar campos de ejemplo
      document.getElementById('dev_vehiculo').value      = 'Toyota Hilux 2022';
      document.getElementById('dev_cliente').value       = 'Juan Carlos Martínez';
      document.getElementById('dev_placa').value         = 'P-1234';
      document.getElementById('dev_fecha_salida').value  = '2024-03-10';
      document.getElementById('dev_fecha_pactada').value = '2024-03-15';
      document.getElementById('res_tarifa').textContent  = '$45.00 / día';
      calcularCargos();
    });
}

function calcularCargos() {
  const fechaSalida  = new Date(document.getElementById('dev_fecha_salida').value  || '2024-03-10');
  const fechaRetorno = new Date(document.getElementById('fecha_devolucion_real').value || new Date());
  const fechaPactada = new Date(document.getElementById('dev_fecha_pactada').value || '2024-03-15');
  const tarifa = 45;

  const diffMs   = fechaRetorno - fechaSalida;
  const dias     = Math.max(1, Math.ceil(diffMs / (1000 * 60 * 60 * 24)));
  const atrasoDs = Math.max(0, Math.ceil((fechaRetorno - fechaPactada) / (1000 * 60 * 60 * 24)));

  document.getElementById('res_dias').textContent   = `${dias} día(s)`;
  document.getElementById('res_atraso').textContent = `$${(atrasoDs * tarifa * 1.5).toFixed(2)}`;

  calcularTotal();
}

function calcularKM() {
  const km = parseInt(document.getElementById('km_retorno').value || 0);
  const kmSalida = 42000; // vendría del contrato
  document.getElementById('res_km').textContent = `${Math.max(0, km - kmSalida)} km recorridos`;
}

function calcularTotal() {
  const base   = 45 * 5; // $45 x 5 días — en producción viene del contrato
  const atraso = parseFloat(document.getElementById('res_atraso').textContent.replace('$','')) || 0;
  const danos  = parseFloat(document.getElementById('cargo_danos').value) || 0;
  document.getElementById('res_total').textContent = `$${(base + atraso + danos).toFixed(2)}`;
}

function capturarFirma() {
  if (typeof getSignatureData === 'function') {
    const data = getSignatureData('signatureCanvas');
    if (data) document.getElementById('firma_cliente_data').value = data;
  }
}

// Init
calcularCargos();
</script>

</body>
</html>