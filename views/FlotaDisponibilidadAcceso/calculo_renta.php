<!doctype html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="mb-2">Calculadora de Tarifas</h2>
                <p class="text-muted">Selecciona un vehículo para calcular el costo de renta</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Seleccionar Vehículo</h5>
                        <form method="GET" action="">
                            <div class="form-group mb-0">
                                <select name="id" class="form-control form-control-lg" onchange="this.form.submit()">
                                    <option value=""> Seleccione un vehículo </option>
                                    <?php
                                    include '../../models/FlotaDisponibilidadAcceso/conexion.php';
                                    $vehiculos = $conexion->query("SELECT id_vehiculo, car_name, modelo, marca, precio_dia, tipo_vehiculo FROM tbVehiculos ORDER BY car_name");
                                    while($vehiculo = $vehiculos->fetch_assoc()){
                                        $selected = (!empty($_GET['id']) && $_GET['id'] == $vehiculo['id_vehiculo']) ? 'selected' : '';
                                        echo "<option value='{$vehiculo['id_vehiculo']}' {$selected}>";
                                        echo "{$vehiculo['car_name']} - {$vehiculo['marca']} {$vehiculo['modelo']} ({$vehiculo['tipo_vehiculo']}) - \${$vehiculo['precio_dia']}/día";
                                        echo "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (!empty($_GET['id'])){
            include("../../controller/FlotaDisponibilidadAcceso/calcular_tarifa.php"); 
        } else {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <h4 class="text-muted mb-3">Selecciona un vehículo para comenzar</h4>
                        <p class="text-muted">Usa el selector de arriba para ver el cálculo de tarifa</p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php } ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Vehículos Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text" id="buscarVehiculo" class="form-control" placeholder="Buscar por nombre, marca o tipo...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaVehiculos">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Marca</th>
                                        <th>Tipo</th>
                                        <th>Precio/día</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vehiculos = $conexion->query("SELECT id_vehiculo, car_name, modelo, marca, tipo_vehiculo, precio_dia FROM tbVehiculos ORDER BY car_name");
                                    while($v = $vehiculos->fetch_assoc()){
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $v['car_name']; ?></strong><br>
                                            <small class="text-muted"><?php echo $v['modelo']; ?></small>
                                        </td>
                                        <td><?php echo $v['marca']; ?></td>
                                        <td><span class="badge badge-secondary"><?php echo $v['tipo_vehiculo']; ?></span></td>
                                        <td class="font-weight-bold">$<?php echo number_format($v['precio_dia'], 2); ?></td>
                                        <td>
                                            <a href="?id=<?php echo $v['id_vehiculo']; ?>" class="btn btn-sm btn-dark">
                                                Calcular
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>