<?php
if (isset($_POST['btn_verificar'])) {
    include '../../models/FlotaDisponibilidadAcceso/conexion.php';
    
    $id_vehiculo = $_POST['id_vehiculo'];
    $fecha_recogida = $_POST['fecha_recogida'];
    $fecha_entrega = $_POST['fecha_entrega'];
    
    if (strtotime($fecha_entrega) <= strtotime($fecha_recogida)) {
        echo "<div class='alert alert-danger mt-4'>La fecha de entrega debe ser posterior a la fecha de recogida</div>";
    } else {
        $sql_vehiculo = "SELECT car_name FROM tbVehiculos WHERE id_vehiculo = $id_vehiculo";
        $result_vehiculo = $conexion->query($sql_vehiculo);
        $vehiculo = $result_vehiculo->fetch_assoc();
        
        $sql_verificar = "SELECT COUNT(*) as total FROM tbReservas WHERE id_vehiculo = $id_vehiculo
                            AND estado IN ('confirmada', 'en_curso') 
                            AND NOT (fecha_entrega < '$fecha_recogida' OR fecha_recogida > '$fecha_entrega')";
        
        $resultado = $conexion->query($sql_verificar);
        $fila = $resultado->fetch_assoc();
        $disponible = ($fila['total'] == 0);
        
        if ($disponible) {
            echo "<div class='alert alert-success mt-4'>
                    <h5>Vehículo Disponible</h5>
                    <p class='mb-0'>El vehículo <strong>{$vehiculo['car_name']}</strong> está disponible del <strong>$fecha_recogida</strong> al <strong>$fecha_entrega</strong></p>
                </div>";
        } else {
            echo "<div class='alert alert-danger mt-4'>
                    <h5>Vehículo NO Disponible</h5>
                    <p class='mb-0'>El vehículo <strong>{$vehiculo['car_name']}</strong> NO está disponible en esas fechas.</p>
                    <p class='mb-0'>Ya existe una reserva confirmada o en curso que se solapa con las fechas seleccionadas.</p>
                </div>";
        }
    }
}
?>