<?php
$titulo = isset($vehiculo) ? 'Editar Vehículo' : 'Nuevo Vehículo';
$seccion = 'vehiculos';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-car me-2" style="color: #137fec;"></i><?= $titulo ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="<?= url('index.php?controller=Vehiculos&action=guardar') ?>" method="POST" enctype="multipart/form-data">
                <div class="col-12 mt-3">
        <label class="form-label">Imágenes del vehículo (máx. 4)</label>
        <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
        <small class="text-muted">Puede seleccionar hasta 4 imágenes. Si ya existen, se reemplazarán.</small>
    </div>

    <?php if (isset($vehiculo)): ?>
        <?php $imagenes = Vehiculo::getImagenes($vehiculo['id_vehiculo']); ?>
        <?php if (!empty($imagenes)): ?>
        <div class="col-12 mt-2">
            <label>Imágenes actuales:</label>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($imagenes as $img): ?>
                    <img src="<?= $img ?>" width="100" class="img-thumbnail">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>


            <form action="<?= url('index.php?controller=Vehiculos&action=guardar') ?>" method="POST">
                <?php if (isset($vehiculo)): ?>
                    <input type="hidden" name="id" value="<?= $vehiculo['id_vehiculo'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del vehículo *</label>
                        <input type="text" name="car_name" class="form-control" value="<?= htmlspecialchars($vehiculo['car_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Marca *</label>
                        <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($vehiculo['marca'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Modelo *</label>
                        <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($vehiculo['modelo'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Año *</label>
                        <input type="number" name="year" class="form-control" value="<?= $vehiculo['year'] ?? date('Y') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($vehiculo['color'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de vehículo *</label>
                        <select name="tipo_vehiculo" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $tipos = ['Sedan', 'SUV', 'Pick-up', 'Convertibles', 'Premium', 'Minivan', 'Compacto', 'Mini', 'Crossover'];
                            $selected = $vehiculo['tipo_vehiculo'] ?? '';
                            foreach ($tipos as $tipo) {
                                $sel = ($tipo == $selected) ? 'selected' : '';
                                echo "<option value=\"$tipo\" $sel>$tipo</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Capacidad (personas)</label>
                        <input type="number" name="capacidad" class="form-control" value="<?= $vehiculo['capacidad'] ?? 5 ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Número de placa *</label>
                        <input type="text" name="numero_placa" class="form-control" value="<?= htmlspecialchars($vehiculo['numero_placa'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio por día ($) *</label>
                        <input type="number" step="0.01" name="precio_dia" class="form-control" value="<?= $vehiculo['precio_dia'] ?? '' ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($vehiculo['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-control" required>
                            <option value="disponible" <?= (isset($vehiculo) && $vehiculo['estado'] == 'disponible') ? 'selected' : '' ?>>Disponible</option>
                            <option value="rentado" <?= (isset($vehiculo) && $vehiculo['estado'] == 'rentado') ? 'selected' : '' ?>>Rentado</option>
                            <option value="mantenimiento" <?= (isset($vehiculo) && $vehiculo['estado'] == 'mantenimiento') ? 'selected' : '' ?>>Mantenimiento</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
                    <a href="<?= url('index.php?controller=Vehiculos&action=index') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>