<?php
$titulo = isset($mantenimiento) ? 'Editar Mantenimiento' : 'Nuevo Mantenimiento';
$seccion = 'mantenimientos';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';

// Si viene un id_vehiculo por GET, lo preseleccionamos
$id_vehiculo_seleccionado = $_GET['id_vehiculo'] ?? 0;
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-wrench me-2" style="color: #137fec;"></i><?= $titulo ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/Sistema_RentACar/index.php?controller=Mantenimientos&action=<?= isset($mantenimiento) ? 'actualizar' : 'crear' ?>" method="POST">
                <?php if (isset($mantenimiento)): ?>
                    <input type="hidden" name="id" value="<?= $mantenimiento['id_mantenimiento'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehículo *</label>
                        <select name="id_vehiculo" class="form-control" required>
                            <option value="">Seleccionar vehículo</option>
                            <?php foreach ($vehiculos as $v): ?>
                                <?php 
                                $selected = '';
                                if (isset($mantenimiento) && $mantenimiento['id_vehiculo'] == $v['id_vehiculo']) {
                                    $selected = 'selected';
                                } elseif ($id_vehiculo_seleccionado == $v['id_vehiculo']) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?= $v['id_vehiculo'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($v['marca'] . ' ' . $v['modelo'] . ' (' . $v['numero_placa'] . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-control" required>
                            <option value="preventivo" <?= (isset($mantenimiento) && $mantenimiento['tipo'] == 'preventivo') ? 'selected' : '' ?>>Preventivo</option>
                            <option value="correctivo" <?= (isset($mantenimiento) && $mantenimiento['tipo'] == 'correctivo') ? 'selected' : '' ?>>Correctivo</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($mantenimiento['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de inicio *</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="<?= $mantenimiento['fecha_inicio'] ?? date('Y-m-d') ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de fin (opcional)</label>
                        <input type="date" name="fecha_fin" class="form-control" value="<?= $mantenimiento['fecha_fin'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo ($)</label>
                        <input type="number" step="0.01" name="costo" class="form-control" value="<?= $mantenimiento['costo'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kilometraje actual</label>
                        <input type="number" name="km_actual" class="form-control" value="<?= $mantenimiento['km_actual'] ?? '' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="programado" <?= (isset($mantenimiento) && $mantenimiento['estado'] == 'programado') ? 'selected' : '' ?>>Programado</option>
                            <option value="en_proceso" <?= (isset($mantenimiento) && $mantenimiento['estado'] == 'en_proceso') ? 'selected' : '' ?>>En proceso</option>
                            <option value="finalizado" <?= (isset($mantenimiento) && $mantenimiento['estado'] == 'finalizado') ? 'selected' : '' ?>>Finalizado</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
                    <a href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=index" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>