<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checklist de Inspección | CarWash RentaCar</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
    <a href="<?= $BASE_URL ?>/index.php?v=devolucion"><i class="fas fa-rotate-left"></i> Devolución</a>
    <a href="<?= $BASE_URL ?>/index.php?v=checklist" class="active"><i class="fas fa-clipboard-check"></i> Checklist</a>

    <div class="nav-section-title">Sistema</div>
    <a href="#"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</a>
  </nav>

  <div class="sidebar-footer">v1.0.0 &copy; 2024 CarWash</div>
</aside>

<div class="main-content">
  <header class="topbar">
    <button class="btn-icon d-lg-none" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h1 class="topbar-title"><i class="fas fa-clipboard-check me-2" style="color:var(--primary-light)"></i>Checklist de <span>Inspección</span></h1>
    <div class="topbar-actions">
      <button class="btn-icon"><i class="fas fa-bell"></i></button>
      <div class="user-chip"><div class="avatar">A</div>Admin</div>
    </div>
  </header>

  <div class="page-body">
    <form action="<?= $BASE_URL ?>/controller/AdministracionClientesOperaciones/ChecklistController.php" method="POST" id="formChecklist">
      <input type="hidden" name="action" value="guardar_checklist">

      <div class="row g-3">

        <!-- COL IZQUIERDA: INFO + CHECKLIST -->
        <div class="col-lg-7">

          <!-- INFO INSPECCIÓN -->
          <div class="card fade-up">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-info-circle"></i> Datos de Inspección</div>
              <div class="d-flex gap-1">
                <span class="badge badge-pending">Salida</span>
              </div>
            </div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Vehículo / Placa *</label>
                  <select class="form-control" name="vehiculo_id" required>
                    <option value="">-- Seleccionar --</option>
                    <option value="1">P-1234 · Toyota Hilux 2022</option>
                    <option value="2">P-5678 · Honda CR-V 2021</option>
                    <option value="3">P-9012 · Hyundai H1 2023</option>
                    <option value="4">P-3456 · Kia Sportage 2022</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Tipo de Inspección *</label>
                  <select class="form-control" name="tipo_inspeccion" id="tipoInspeccion" required>
                    <option value="salida">Pre-Renta (Salida)</option>
                    <option value="devolucion">Post-Renta (Devolución)</option>
                    <option value="mantenimiento">Mantenimiento Periódico</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Inspector *</label>
                  <input class="form-control" type="text" name="inspector" placeholder="Nombre del inspector" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Fecha y Hora *</label>
                  <input class="form-control" type="datetime-local" name="fecha_inspeccion"
                         value="<?= date('Y-m-d\TH:i') ?>" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">KM Actual *</label>
                  <input class="form-control" type="number" name="km_inspeccion" placeholder="Kilometraje actual" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Nivel de Combustible *</label>
                  <select class="form-control" name="nivel_combustible" required>
                    <option value="lleno">Tanque lleno ████</option>
                    <option value="tres_cuartos">¾ Tanque ███░</option>
                    <option value="medio" selected>½ Tanque ██░░</option>
                    <option value="cuarto">¼ Tanque █░░░</option>
                    <option value="vacio">Casi vacío ░░░░</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- ── SECCIÓN 1: EXTERIOR ── -->
          <div class="card fade-up delay-1">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-car"></i> 1. Carrocería y Exterior</div>
            </div>
            <div class="card-body">
              <div class="checklist-section">
                <div class="checklist-section-title">Panel Frontal</div>
                <?php
                $itemsExterior = [
                  ['id'=>'ext_01','label'=>'Capó sin abolladuras ni rayones'],
                  ['id'=>'ext_02','label'=>'Parabrisas delantero sin fisuras'],
                  ['id'=>'ext_03','label'=>'Faros delanteros funcionando (low / high beam)'],
                  ['id'=>'ext_04','label'=>'Luces de niebla / direccionales'],
                  ['id'=>'ext_05','label'=>'Parachoque delantero intacto'],
                  ['id'=>'ext_06','label'=>'Placa delantera presente y legible'],
                ];
                foreach ($itemsExterior as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>

              <div class="checklist-section">
                <div class="checklist-section-title">Laterales y Puertas</div>
                <?php
                $itemsLat = [
                  ['id'=>'lat_01','label'=>'Puerta delantera izquierda sin daños'],
                  ['id'=>'lat_02','label'=>'Puerta delantera derecha sin daños'],
                  ['id'=>'lat_03','label'=>'Puerta trasera izquierda sin daños'],
                  ['id'=>'lat_04','label'=>'Puerta trasera derecha sin daños'],
                  ['id'=>'lat_05','label'=>'Espejos retrovisores laterales intactos'],
                  ['id'=>'lat_06','label'=>'Molduras y filetes sin desprendimientos'],
                ];
                foreach ($itemsLat as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>

              <div class="checklist-section">
                <div class="checklist-section-title">Panel Trasero</div>
                <?php
                $itemsTras = [
                  ['id'=>'tra_01','label'=>'Parabrisas trasero sin fisuras'],
                  ['id'=>'tra_02','label'=>'Faros traseros y stop funcionando'],
                  ['id'=>'tra_03','label'=>'Luces de reversa y emergencia'],
                  ['id'=>'tra_04','label'=>'Parachoque trasero intacto'],
                  ['id'=>'tra_05','label'=>'Placa trasera presente y legible'],
                  ['id'=>'tra_06','label'=>'Maletero cierra correctamente'],
                ];
                foreach ($itemsTras as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- ── SECCIÓN 2: NEUMÁTICOS ── -->
          <div class="card fade-up delay-1">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-circle-dot"></i> 2. Neumáticos y Llantas</div>
            </div>
            <div class="card-body">
              <div class="checklist-section">
                <?php
                $itemsNeum = [
                  ['id'=>'neu_01','label'=>'Neumático delantero izquierdo en buen estado'],
                  ['id'=>'neu_02','label'=>'Neumático delantero derecho en buen estado'],
                  ['id'=>'neu_03','label'=>'Neumático trasero izquierdo en buen estado'],
                  ['id'=>'neu_04','label'=>'Neumático trasero derecho en buen estado'],
                  ['id'=>'neu_05','label'=>'Llanta de repuesto presente y en condición'],
                  ['id'=>'neu_06','label'=>'Gato hidráulico y herramientas de emergencia'],
                  ['id'=>'neu_07','label'=>'Tapones de válvulas presentes'],
                ];
                foreach ($itemsNeum as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- ── SECCIÓN 3: INTERIOR ── -->
          <div class="card fade-up delay-2">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-couch"></i> 3. Interior del Vehículo</div>
            </div>
            <div class="card-body">
              <div class="checklist-section">
                <div class="checklist-section-title">Tablero y Controles</div>
                <?php
                $itemsInt = [
                  ['id'=>'int_01','label'=>'Tablero sin luces de advertencia activas'],
                  ['id'=>'int_02','label'=>'Indicadores (velocímetro, tacómetro) funcionando'],
                  ['id'=>'int_03','label'=>'Aire acondicionado en funcionamiento'],
                  ['id'=>'int_04','label'=>'Radio / sistema multimedia operativo'],
                  ['id'=>'int_05','label'=>'Bocina funcionando correctamente'],
                  ['id'=>'int_06','label'=>'Limpiaparabrisas y agua del parabrisas'],
                  ['id'=>'int_07','label'=>'Espejo retrovisor interior sin daños'],
                ];
                foreach ($itemsInt as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>

              <div class="checklist-section">
                <div class="checklist-section-title">Asientos y Limpieza</div>
                <?php
                $itemsAsien = [
                  ['id'=>'asi_01','label'=>'Asiento conductor sin roturas ni manchas'],
                  ['id'=>'asi_02','label'=>'Asiento copiloto sin roturas ni manchas'],
                  ['id'=>'asi_03','label'=>'Asientos traseros sin roturas ni manchas'],
                  ['id'=>'asi_04','label'=>'Cinturones de seguridad funcionando (todos)'],
                  ['id'=>'asi_05','label'=>'Alfombras limpias y sin roturas'],
                  ['id'=>'asi_06','label'=>'Techo interior sin manchas ni desprendimientos'],
                ];
                foreach ($itemsAsien as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- ── SECCIÓN 4: MECÁNICO ── -->
          <div class="card fade-up delay-2">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-gear"></i> 4. Estado Mecánico</div>
            </div>
            <div class="card-body">
              <div class="checklist-section">
                <?php
                $itemsMec = [
                  ['id'=>'mec_01','label'=>'Nivel de aceite del motor correcto'],
                  ['id'=>'mec_02','label'=>'Nivel de refrigerante correcto'],
                  ['id'=>'mec_03','label'=>'Nivel de líquido de frenos correcto'],
                  ['id'=>'mec_04','label'=>'Nivel de dirección hidráulica correcto'],
                  ['id'=>'mec_05','label'=>'Correa de distribución/serpentina en buenas condiciones'],
                  ['id'=>'mec_06','label'=>'Batería con carga correcta (sin corrosión)'],
                  ['id'=>'mec_07','label'=>'Frenos responden adecuadamente'],
                  ['id'=>'mec_08','label'=>'Sin fugas visibles de fluidos'],
                  ['id'=>'mec_09','label'=>'Extintor de incendios presente y vigente'],
                  ['id'=>'mec_10','label'=>'Triángulos de seguridad vial presentes'],
                ];
                foreach ($itemsMec as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- ── SECCIÓN 5: DOCUMENTACIÓN ── -->
          <div class="card fade-up delay-3">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-folder-open"></i> 5. Documentación</div>
            </div>
            <div class="card-body">
              <div class="checklist-section">
                <?php
                $itemsDoc = [
                  ['id'=>'doc_01','label'=>'Tarjeta de circulación / registro del vehículo'],
                  ['id'=>'doc_02','label'=>'Póliza de seguro vigente en el vehículo'],
                  ['id'=>'doc_03','label'=>'Manual del propietario presente'],
                  ['id'=>'doc_04','label'=>'Llaves del vehículo (principal + copia si aplica)'],
                  ['id'=>'doc_05','label'=>'Control de alarma / llave inteligente'],
                ];
                foreach ($itemsDoc as $it): ?>
                <div class="checklist-item" onclick="toggleItem(this,'<?= $it['id'] ?>')">
                  <div class="custom-checkbox"></div>
                  <input type="hidden" name="<?= $it['id'] ?>" value="0" id="inp_<?= $it['id'] ?>">
                  <span class="item-label"><?= $it['label'] ?></span>
                  <span class="item-status" id="sts_<?= $it['id'] ?>" style="color:var(--text-muted)">Pendiente</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

        </div><!-- /col izq -->

        <!-- COL DERECHA -->
        <div class="col-lg-5">

          <div class="card fade-up" style="position:sticky;top:80px">
            <div class="card-header">
              <div class="card-title"><i class="fas fa-chart-pie"></i> Progreso de Inspección</div>
            </div>
            <div class="card-body">

              <div class="progress-bar-wrapper mb-2">
                <div class="progress-bar" id="checklistProgress" style="width:0%"></div>
              </div>
              <div style="font-size:.82rem;color:var(--text-muted);text-align:center;margin-bottom:18px" id="checklistPct">
                0 / 0 ítems completados (0%)
              </div>

              <hr style="border-color:var(--card-border)">

              <div class="form-group mt-3">
                <label class="form-label">Observaciones Generales</label>
                <textarea class="form-control" name="observaciones_generales" rows="4"
                          placeholder="Notas adicionales del inspector..."></textarea>
              </div>

              <div class="form-group">
                <label class="form-label">Firma del Inspector</label>
                <div class="signature-wrapper">
                  <canvas id="signatureCanvas"></canvas>
                </div>
                <div class="d-flex justify-content-between mt-1">
                  <small style="color:var(--text-muted);font-size:.72rem"><i class="fas fa-pen-nib"></i> Firme aquí</small>
                  <button type="button" class="btn btn-outline btn-sm" onclick="clearSignature('signatureCanvas')">
                    <i class="fas fa-eraser"></i> Limpiar
                  </button>
                </div>
                <input type="hidden" name="firma_inspector" id="firma_inspector_data">
              </div>

              <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-outline flex-fill" onclick="window.print()">
                  <i class="fas fa-print"></i> Imprimir
                </button>
                <button type="submit" class="btn btn-primary flex-fill" onclick="submitChecklist()">
                  <i class="fas fa-save"></i> Guardar
                </button>
              </div>
              <button type="button" class="btn btn-success w-100 mt-2" onclick="marcarTodos()">
                <i class="fas fa-check-double"></i> Marcar Todos
              </button>
            </div>
          </div>

        </div><!-- /col der -->
      </div><!-- /row -->
    </form>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $BASE_URL ?>/assets/js/AdministracionClientesOperaciones/clientes.js"></script>

<script>
function toggleItem(el, id) {
  el.classList.toggle('checked');
  const cb  = el.querySelector('.custom-checkbox');
  const sts = document.getElementById(`sts_${id}`);
  const inp = document.getElementById(`inp_${id}`);

  if (el.classList.contains('checked')) {
    cb.innerHTML = '<i class="fas fa-check" style="color:white;font-size:.7rem"></i>';
    if (sts) { sts.textContent = 'OK'; sts.style.color = 'var(--success)'; }
    if (inp) inp.value = 1;
  } else {
    cb.innerHTML = '';
    if (sts) { sts.textContent = 'Pendiente'; sts.style.color = 'var(--text-muted)'; }
    if (inp) inp.value = 0;
  }
  if (typeof updateChecklistProgress === 'function') updateChecklistProgress();
}

function marcarTodos() {
  document.querySelectorAll('.checklist-item:not(.checked)').forEach(item => item.click());
  if (typeof showToast === 'function') showToast('Todos los ítems marcados como correctos', 'success');
}

function submitChecklist() {
  if (typeof getSignatureData === 'function') {
    const data = getSignatureData('signatureCanvas');
    if (data) document.getElementById('firma_inspector_data').value = data;
  }

  const total   = document.querySelectorAll('.checklist-item').length;
  const checked = document.querySelectorAll('.checklist-item.checked').length;

  if (checked < total) {
    if (!confirm(`Hay ${total - checked} ítems sin revisar. ¿Desea guardar de todas formas?`)) return;
  }
  if (typeof showToast === 'function') showToast('Checklist guardado exitosamente', 'success');
}

document.addEventListener('DOMContentLoaded', () => {
  if (typeof updateChecklistProgress === 'function') updateChecklistProgress();
});
</script>

</body>
</html>