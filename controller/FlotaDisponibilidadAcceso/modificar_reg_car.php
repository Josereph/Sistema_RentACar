<?php
if (isset($_POST["btn-editar"])) {
    if (!empty($_POST["id"]) && !empty($_POST["nombre-vehiculo"]) && !empty($_POST["modelo-vehiculo"])) {
        include("../Model/conexion.php");
        $id = $_POST["id"]; 
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

        $sql = "UPDATE tbVehiculos SET 
                car_name = '$nombre', 
                modelo = '$modelo', 
                year = '$year', 
                marca = '$marca', 
                color = '$color', 
                tipo_vehiculo = '$tipo_vehiculo', 
                capacidad = '$capacidad', 
                numero_placa = '$numero_placa', 
                precio_dia = '$precio_dia', 
                descripcion = '$descripcion', 
                estado = '$estado' 
                WHERE id_vehiculo = $id";
        if ($conexion->query($sql)) {
            header("location: CRUD_Vehiculos.php?mensaje=actualizado");
            exit(); 
        } else {
            echo "<div class='alert alert-danger'>Error al actualizar datos: " . $conexion->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, complete todos los campos obligatorios.</div>";
    }
}
?>