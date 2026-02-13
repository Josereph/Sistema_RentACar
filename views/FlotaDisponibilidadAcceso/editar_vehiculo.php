<?php
include '../../models/FlotaDisponibilidadAcceso/conexion.php';
if (empty($_GET["id"])) {
    header("location: CRUD_Vehiculos.php");
    exit();
}
$id = $_GET["id"];
$sql = $conexion->query("SELECT * FROM tbVehiculos WHERE id_vehiculo = $id");
?>
<!doctype html>
<html lang="es">
<head>
    <title>Editar Vehículo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h3 class="mb-0">Modificar Datos del Vehículo</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <?php 
                            include "../../controller/FlotaDisponibilidadAcceso/modificar_reg_car.php"; 
                            while($datos = $sql->fetch_object()) { ?>
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nombre del vehículo</label>
                                    <input type="text" class="form-control" name="nombre-vehiculo"value="<?= $datos->car_name ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Modelo</label>
                                        <input type="text" class="form-control" name="modelo-vehiculo" value="<?= $datos->modelo ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Año</label>
                                        <input type="number" class="form-control" name="year-vehiculo" value="<?= $datos->year ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Marca</label>
                                        <input type="text" class="form-control" name="marca-vehiculo"  value="<?= $datos->marca ?>">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Color</label>
                                        <input type="text" class="form-control" name="color-vehiculo" value="<?= $datos->color ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Tipo de vehículo</label>
                                    <select class="form-control" name="tipo-vehiculo">
                                        <option <?= $datos->tipo_vehiculo == "Sedan" ? "selected" : "" ?> value="Sedan">Sedan</option>
                                        <option <?= $datos->tipo_vehiculo == "SUV" ? "selected" : "" ?> value="SUV">SUV</option>
                                        <option <?= $datos->tipo_vehiculo == "Pick-up" ? "selected" : "" ?> value="Pick-up">Pick-up</option> 
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Capacidad</label>
                                        <input type="number" class="form-control" name="capacidad-vehiculo" value="<?= $datos->capacidad ?>">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Número de placa</label>
                                        <input type="text" class="form-control" name="numero-placa" value="<?= $datos->numero_placa ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Precio por día</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control" name="precio-dia"  value="<?= $datos->precio_dia ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Descripción</label>
                                    <textarea class="form-control" name="descripcion-vehiculo" rows="3"><?= $datos->descripcion ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Estado</label>
                                    <select class="form-control" name="estado-vehiculo">
                                        <option <?= $datos->estado == "disponible" ? "selected" : "" ?> value="disponible">Disponible</option>
                                        <option <?= $datos->estado == "rentado" ? "selected" : "" ?> value="rentado">Rentado</option>
                                        <option <?= $datos->estado == "mantenimiento" ? "selected" : "" ?> value="mantenimiento">Mantenimiento</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between pt-3">
                                    <a href="CRUD_Vehiculos.php" class="btn btn-outline-secondary px-5">Cancelar</a>
                                    <button type="submit" class="btn btn-dark px-5" name="btn-editar">Guardar Cambios</button>
                                </div>
                            <?php }?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>