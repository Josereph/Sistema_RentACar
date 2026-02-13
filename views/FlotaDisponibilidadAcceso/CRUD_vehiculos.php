<!doctype html>
<html lang="en">
<head>
    <title>CRUD Vehículos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid p-4">
        <h1 class="text-center mb-4">CRUD Vehiculos</h1>
    <?php
    include("../Model/conexion.php");
    include("../Controller/eliminar_vehiculo.php");
    ?>
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-dark" style="border-radius:0;" data-toggle="modal" data-target="#modalFormulario">
                + Agregar vehículo
            </button>
        </div>
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Placa</th>
                        <th>Precio/Día</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-vehiculos">
                    <?php
                    include '../Model/conexion.php';
                    include("../Controller/registro_vehiculo.php"); 
                    $sql = "SELECT * FROM tbVehiculos";
                    $result = $conexion->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id_vehiculo"] . "</td>"; 
                            echo "<td>" . $row["car_name"] . "</td>";
                            echo "<td>" . $row["modelo"] . "</td>";
                            echo "<td>" . $row["year"] . "</td>";
                            echo "<td>" . $row["marca"] . "</td>";
                            echo "<td>" . $row["color"] . "</td>";
                            echo "<td>" . $row["tipo_vehiculo"] . "</td>";
                            echo "<td>" . $row["capacidad"] . "</td>";
                            echo "<td>" . $row["numero_placa"] . "</td>";
                            echo "<td>$" . number_format($row["precio_dia"], 2) . "</td>";
                            echo "<td>" . $row["descripcion"] . "</td>";
                            echo "<td>" . $row["estado"] . "</td>";
                            echo '<td>
                                <a href="editar_vehiculo.php?id=' . $row["id_vehiculo"] . '" class="btn btn-sm btn-warning">Editar</a> 
                                <a href="CRUD_Vehiculos.php?id=' . $row["id_vehiculo"] . '" class="btn btn-sm btn-danger text-white">Eliminar</a>
                            </td>';
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalFormulario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Vehículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Nombre del vehículo</label>
                            <input type="text" class="form-control" name="nombre-vehiculo" placeholder="Ingrese el nombre">
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label>Modelo</label>
                                <input type="text" class="form-control" name="modelo-vehiculo">
                            </div>
                            <div class="col-6 form-group">
                                <label>Año</label>
                                <input type="number" class="form-control" name="year-vehiculo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label>Marca</label>
                                <input type="text" class="form-control" name="marca-vehiculo">
                            </div>
                            <div class="col-6 form-group">
                                <label>Color</label>
                                <input type="text" class="form-control" name="color-vehiculo">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tipo de vehículo</label>
                            <select class="form-control" name="tipo-vehiculo">
                                <option value="Sedan">Sedan</option>
                                <option value="SUV">SUV</option>
                                <option value="Pick-up">Pick-up</option> 
                                <option value="Convertibles">Convertibles</option> 
                                <option value="Premium">Premium</option>
                                <option value="Minivan">Minivan</option>
                                <option value="Compacto">Compacto</option>
                                <option value="Mini">Mini</option>
                                <option value="Crossover">Crossover</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <label>Capacidad</label>
                                <input type="number" class="form-control" name="capacidad-vehiculo">
                            </div>
                            <div class="col-6 form-group">
                                <label>Número de placa</label>
                                <input type="text" class="form-control" name="numero-placa">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Precio por día</label>
                            <input type="number" class="form-control" name="precio-dia">
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion-vehiculo" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" name="estado-vehiculo">
                                <option value="disponible">Disponible</option>
                                <option value="rentado">Rentado</option>
                                <option value="mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-registrar-form" name="btn-registrar">Guardar Vehículo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>