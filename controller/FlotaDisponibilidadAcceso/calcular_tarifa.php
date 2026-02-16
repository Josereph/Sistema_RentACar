<?php
$id = $_GET["id"];
$resultado_precio = $conexion->query("SELECT precio_dia, car_name, modelo, marca, tipo_vehiculo FROM tbVehiculos WHERE id_vehiculo=$id");
$resultado_dias = $conexion->query("SELECT fecha_recogida, fecha_entrega, DATEDIFF(fecha_entrega, fecha_recogida) AS dias_transcurridos FROM tbReservas WHERE estado='confirmada' AND id_vehiculo=$id");

if ($resultado_dias->num_rows>0 && $resultado_precio->num_rows>0){
    $fila_precio = $resultado_precio->fetch_assoc();
    $precio = $fila_precio['precio_dia'];
    $car_name = $fila_precio['car_name'];
    $modelo = $fila_precio['modelo'];
    $marca = $fila_precio['marca'];
    $tipo_vehiculo = $fila_precio['tipo_vehiculo'];

    $fila_dias = $resultado_dias->fetch_assoc();
    $dias = $fila_dias['dias_transcurridos'];
    $fecha_recogida = $fila_dias['fecha_recogida'];
    $fecha_entrega = $fila_dias['fecha_entrega'];

    if ($dias == 0) {
        $dias = 1;
    }
    
    $subtotal = ($dias * $precio);
    $IVA = $subtotal * 0.13;
    $total = $subtotal + $IVA;
    
    ?>
    
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Información del Vehículo</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-1"><?php echo $car_name; ?></h4>
                    <p class="text-muted mb-3"><?php echo $marca . " " . $modelo; ?></p>
                    
                    <div class="mb-4">
                        <span class="badge badge-secondary p-2"><?php echo $tipo_vehiculo; ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Periodo de Renta</small>
                        <div class="row">
                            <div class="col-6">
                                <strong>Recogida:</strong><br>
                                <?php echo date('d/m/Y', strtotime($fecha_recogida)); ?>
                            </div>
                            <div class="col-6">
                                <strong>Entrega:</strong><br>
                                <?php echo date('d/m/Y', strtotime($fecha_entrega)); ?>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="mb-0"><?php echo $dias; ?></h3>
                            <small class="text-muted">Días de renta</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">$<?php echo number_format($precio, 2); ?></h3>
                            <small class="text-muted">Por día</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Desglose de Tarifa</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted">
                                        Subtotal 
                                        <small class="d-block">(<?php echo $dias; ?> días × $<?php echo number_format($precio, 2); ?>)</small>
                                    </td>
                                    <td class="text-right font-weight-bold h5 mb-0">
                                        $<?php echo number_format($subtotal, 2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">
                                        IVA (13%)
                                    </td>
                                    <td class="text-right font-weight-bold h5 mb-0">
                                        $<?php echo number_format($IVA, 2); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="row align-items-center py-3 bg-light rounded">
                        <div class="col-6">
                            <h5 class="mb-0">Total a Pagar</h5>
                        </div>
                        <div class="col-6 text-right">
                            <h2 class="mb-0 font-weight-bold">
                                $<?php echo number_format($total, 2); ?>
                            </h2>
                        </div>
                    </div>
                    <button class="btn btn-dark btn-block btn-lg mt-4">
                        Confirmar Reserva
                    </button>
                    
                </div>
            </div>
        </div>
    </div>
    
    <?php
    
} else {
    ?>
    
    <div class="row">
        <div class="col-12">
            <div class="alert alert-secondary" role="alert">
                <h5 class="alert-heading">Vehículo sin reservas confirmadas</h5>
                <p class="mb-0">Este vehículo no tiene reservas en estado "confirmada" para calcular la tarifa.</p>
            </div>
        </div>
    </div>
    
    <?php
}
?>