<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
        <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Verificar Disponibilidad de Vehículos</h2>
            <a href="disponivilidad_vehuculo.php" class="btn btn-secondary">Reiniciar</a>
        </div>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="vehiculo">Seleccionar Vehículo</label>
                            <select class="form-control" id="vehiculo" name="id_vehiculo" required>
                                <option value=""> Seleccione un vehículo </option>
                                <?php
                                include '../../models/FlotaDisponibilidadAcceso/conexion.php';
                                $sql = "SELECT id_vehiculo, car_name, marca, modelo, tipo_vehiculo, precio_dia 
                                        FROM tbVehiculos 
                                        WHERE estado != 'mantenimiento' 
                                        ORDER BY tipo_vehiculo, car_name";
                                $resultado = $conexion->query($sql);
                                while ($vehiculo = $resultado->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($_POST['id_vehiculo']) && $_POST['id_vehiculo'] == $vehiculo['id_vehiculo']) {
                                        $selected = 'selected';
                                    }
                                    echo "<option value='{$vehiculo['id_vehiculo']}' $selected>
                                            {$vehiculo['car_name']} - {$vehiculo['marca']} {$vehiculo['modelo']} ({$vehiculo['tipo_vehiculo']}) - \${$vehiculo['precio_dia']}/día
                                        </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fecha_recogida">Fecha de Recogida</label>
                            <input type="date" class="form-control" id="fecha_recogida" name="fecha_recogida" value="<?php echo isset($_POST['fecha_recogida']) ? $_POST['fecha_recogida'] : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fecha_entrega">Fecha de Entrega</label>
                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="<?php echo isset($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" name="btn_verificar" class="btn btn-primary btn-block">Verificar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include("../../controller/FlotaDisponibilidadAcceso/verificar_disponibilidad.php"); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>