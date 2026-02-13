<?php
if(!empty($_GET["id"])){
    $id=$_GET["id"];
    $sql=$conexion->query("DELETE FROM tbVehiculos WHERE id_vehiculo=$id");
    if($sql==1){
        echo "<script>alert('Registro eliminado exitosamente');window.location='../View/CRUD_Vehiculos.php';</script>";
    }
    else{
        echo "<script>alert('Error al eliminar el registro');window.location='../View/CRUD_Vehiculos.php';</script>";
    }
}
?>