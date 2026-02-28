<?php
$titulo = 'Nuevo Vehículo';
$seccion = 'vehiculos';
include PROJECT_ROOT_FS . '/views/empleado/layouts/header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-car me-2" style="color: #137fec;"></i>Nuevo Vehículo</h2>
        </div>
    </div>

    <?php if (isset($_SESSION['error_imagenes'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_imagenes'] ?></div>
        <?php unset($_SESSION['error_imagenes']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?= url('index.php?area=empleado&controller=VehiculosEmpleado&action=guardar') ?>" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del vehículo *</label>
                        <input type="text" name="car_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Marca *</label>
                        <input type="text" name="marca" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Modelo *</label>
                        <input type="text" name="modelo" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Año *</label>
                        <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo de vehículo *</label>
                        <select name="tipo_vehiculo" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $tipos = ['Sedan', 'SUV', 'Pick-up', 'Convertibles', 'Premium', 'Minivan', 'Compacto', 'Mini', 'Crossover'];
                            foreach ($tipos as $tipo) {
                                echo "<option value=\"$tipo\">$tipo</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Capacidad (personas)</label>
                        <input type="number" name="capacidad" class="form-control" value="5">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Número de placa *</label>
                        <input type="text" name="numero_placa" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio por día ($) *</label>
                        <input type="number" step="0.01" name="precio_dia" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-control" required>
                            <option value="disponible">Disponible</option>
                            <option value="rentado">Rentado</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Imágenes del vehículo (máx. 4)</label>
                        <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Puede seleccionar hasta 4 imágenes.</small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
                    <a href="<?= url('index.php?area=empleado&controller=VehiculosEmpleado&action=index') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/footer.php'; ?>