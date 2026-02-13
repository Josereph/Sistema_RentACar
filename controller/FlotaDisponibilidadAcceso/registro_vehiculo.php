<?php
if (isset($_POST["btn-registrar"])) {
    include("../../models/FlotaDisponibilidadAcceso/conexion.php");
    $nombre = $_POST["nombre-vehiculo"];
    $modelo = $_POST["modelo-vehiculo"];
    $year = $_POST["year-vehiculo"];
    $marca = $_POST["marca-vehiculo"];
    $color = $_POST["color-vehiculo"];
    $tipo_vehiculo = $_POST["tipo-vehiculo"];
    $capacidad = $_POST["capacidad-vehiculo"];
    $numero_placa = $_POST["numero-placa"];
    $precio_dia = $_POST["precio-dia"];
    $descripcion = $_POST["descripcion-vehiculo"];
    $estado = $_POST["estado-vehiculo"];

    $sql = "INSERT INTO tbVehiculos 
    (car_name, modelo, year, marca, color, tipo_vehiculo, capacidad, numero_placa, precio_dia, descripcion, estado)
    VALUES 
    ('$nombre', '$modelo', '$year', '$marca', '$color', '$tipo_vehiculo', '$capacidad', '$numero_placa', '$precio_dia', '$descripcion', '$estado')";

    if ($conexion->query($sql)) {
        echo "<div class='alert alert-success'>Vehículo registrado exitosamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: {$conexion->error}</div>";
    }
}
?>