<?php
// views/AdministracionClientesOperaciones/checklist.php
// Entra por index.php (router). Asumimos que PROJECT_ROOT_FS y url() existen.

// Variables esperadas desde el controller (si no vienen, no truena):
// $devolucion (array)  -> info de la devolución
// $items (array)       -> lista de items desde tbChecklistItems (id_item, nombre)
// $checks (array)      -> estado previo por item: [id_item => ['estado'=>'ok|falla','nota'=>'...']]

$devolucion = $devolucion ?? null;
$items      = $items ?? [];
$checks     = $checks ?? [];

$id_devolucion = $devolucion['id_devolucion'] ?? ($_GET['id_devolucion'] ?? null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checklist | GO CAR</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Base visual (la que ya usas en el resto) -->
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
  <!-- Estilos específicos del checklist -->
  <link rel="stylesheet" href="<?= url('assets/css/AdministracionClientesOperaciones/checklist.css') ?>">
</head>

<body>

<?php include PROJECT_ROOT_FS . '/views/layouts/navbar.php'; ?>

<main class="container py-4">

  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
      <h1 class="h4 m-0">
        <i class="fas fa-clipboard-check me-2"></i>
        Checklist de Inspección
      </h1>
      <div class="text-muted small">
        <?php if ($id_devolucion): ?>
          Devolución: <strong>#<?= htmlspecialchars((string)$id_devolucion) ?></strong>
        <?php else: ?>
          <span class="text-danger">Falta id_devolucion en la URL.</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="<?= url('index.php?controller=Devolucion&action=index') ?>">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
      <button type="button" class="btn btn-outline-primary" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Imprimir
      </button>
    </div>
  </div>

  <?php if (!$id_devolucion): ?>
    <div class="alert alert-danger">
      No se puede abrir el checklist sin <strong>id_devolucion</strong>.
      Ejemplo: <code>?controller=Checklist&action=index&id_devolucion=1</code>
    </div>
  <?php endif; ?>

  <form
    action="<?= url('index.php?controller=Checklist&action=guardar') ?>"
    method="POST"
    id="formChecklist"
    class="row g-3"
  >
    <input type="hidden" name="id_devolucion" value="<?= htmlspecialchars((string)$id_devolucion) ?>">

    <!-- IZQUIERDA -->
    <div class="col-lg-7">

      <!-- Datos de inspección (mínimo en vista; lo real viene de BD/controlador) -->
      <div class="card shadow-sm mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="fw-semibold">
            <i class="fas fa-info-circle me-2"></i> Datos de Inspección
          </div>
          <span class="badge text-bg-warning">Checklist</span>
        </div>

        <div class="card-body">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Inspector</label>
              <input class="form-control" type="text" name="inspector" placeholder="Nombre del inspector" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Fecha y Hora</label>
              <input class="form-control" type="datetime-local" name="fecha_inspeccion"
                     value="<?= date('Y-m-d\TH:i') ?>" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">KM Actual</label>
              <input class="form-control" type="number" name="km_inspeccion" placeholder="Kilometraje actual" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nivel de Combustible</label>
              <select class="form-select" name="nivel_combustible" required>
                <option value="lleno">Tanque lleno</option>
                <option value="tres_cuartos">¾ Tanque</option>
                <option value="medio" selected>½ Tanque</option>
                <option value="cuarto">¼ Tanque</option>
                <option value="vacio">Casi vacío</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Checklist items (desde BD) -->
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">
          <i class="fas fa-list-check me-2"></i> Ítems del Checklist
        </div>

        <div class="card-body">
          <?php if (empty($items)): ?>
            <div class="alert alert-info m-0">
              No hay ítems en <code>tbChecklistItems</code>. Inserta los ítems y recarga.
            </div>
          <?php else: ?>

            <div class="checklist-grid">
              <?php foreach ($items as $it): ?>
                <?php
                  $id_item = (int)$it['id_item'];
                  $nombre  = (string)$it['nombre'];

                  $prevEstado = $checks[$id_item]['estado'] ?? null; // ok|falla|null
                  $prevNota   = $checks[$id_item]['nota'] ?? '';

                  $isOk    = ($prevEstado === 'ok');
                  $isFalla = ($prevEstado === 'falla');
                ?>

                <div class="check-item <?= $isOk ? 'is-ok' : '' ?> <?= $isFalla ? 'is-falla' : '' ?>"
                     data-id-item="<?= $id_item ?>">
                  <div class="check-item__top">
                    <div class="check-item__title">
                      <i class="fas fa-circle-check me-2"></i>
                      <?= htmlspecialchars($nombre) ?>
                    </div>

                    <div class="btn-group btn-group-sm" role="group">
                      <button type="button" class="btn btn-outline-success"
                              onclick="setEstado(<?= $id_item ?>,'ok')">
                        OK
                      </button>
                      <button type="button" class="btn btn-outline-danger"
                              onclick="setEstado(<?= $id_item ?>,'falla')">
                        Falla
                      </button>
                    </div>
                  </div>

                  <div class="check-item__bottom">
                    <div class="small text-muted">
                      Estado: <span id="estado_txt_<?= $id_item ?>"><?= $prevEstado ? strtoupper($prevEstado) : 'PENDIENTE' ?></span>
                    </div>

                    <input type="hidden" name="items[<?= $id_item ?>][estado]" id="estado_<?= $id_item ?>"
                           value="<?= htmlspecialchars((string)$prevEstado) ?>">

                    <label class="form-label mt-2 mb-1">Nota (opcional)</label>
                    <input type="text" class="form-control form-control-sm"
                           name="items[<?= $id_item ?>][nota]" value="<?= htmlspecialchars((string)$prevNota) ?>"
                           placeholder="Ej: Rayón leve / Falta triángulos / etc.">
                  </div>
                </div>

              <?php endforeach; ?>
            </div>

          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- DERECHA -->
    <div class="col-lg-5">

      <div class="card shadow-sm sticky-top" style="top: 90px;">
        <div class="card-header fw-semibold">
          <i class="fas fa-chart-pie me-2"></i> Progreso
        </div>

        <div class="card-body">
          <div class="progress mb-2" style="height: 10px;">
            <div class="progress-bar" id="checklistProgress" style="width:0%"></div>
          </div>

          <div class="small text-muted text-center" id="checklistPct">
            0 / 0 completados (0%)
          </div>

          <hr>

          <div class="mb-3">
            <label class="form-label">Observaciones Generales</label>
            <textarea class="form-control" name="observaciones_generales" rows="4"
                      placeholder="Notas adicionales del inspector..."></textarea>
          </div>

          <div class="mb-2">
            <label class="form-label">Firma del Inspector</label>
            <div class="signature-wrapper">
              <canvas id="signatureCanvas"></canvas>
            </div>

            <div class="d-flex justify-content-between mt-2">
              <small class="text-muted"><i class="fas fa-pen-nib me-1"></i>Firme aquí</small>
              <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSignature()">
                <i class="fas fa-eraser me-1"></i> Limpiar
              </button>
            </div>

            <input type="hidden" name="firma_inspector" id="firma_inspector_data">
          </div>

          <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-outline-dark flex-fill" onclick="marcarTodosOk()">
              <i class="fas fa-check-double me-1"></i> Marcar todo OK
            </button>
            <button type="submit" class="btn btn-primary flex-fill" onclick="beforeSubmitChecklist(event)">
              <i class="fas fa-save me-1"></i> Guardar
            </button>
          </div>

        </div>
      </div>

    </div>

  </form>

</main>

<?php include PROJECT_ROOT_FS . '/views/layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('assets/js/AdministracionClientesOperaciones/checklist.js') ?>"></script>

</body>
</html>