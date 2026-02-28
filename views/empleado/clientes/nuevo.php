<?php
$titulo = 'Nuevo Cliente';
$seccion = 'clientes';
include PROJECT_ROOT_FS . '/views/empleado/layouts/header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/navbar.php';
?>
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-user-plus me-2" style="color: #137fec;"></i>Nuevo Cliente</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=crear') ?>" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DUI *</label>
                        <input type="text" name="dui" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="telefono" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Guardar</button>
                    <a href="<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=index') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/footer.php'; ?>