<?php

require_once '../../config/conexion.php';

if(!isset($_GET['id_reserva'])){
    echo "Reserva no especificada";
    exit;
}

$id_reserva = $_GET['id_reserva'];

$sql = "SELECT pdf_path FROM Contratos WHERE id_reserva = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_reserva);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows == 0){
    echo "Contrato no encontrado";
    exit;
}

$contrato = $resultado->fetch_assoc();

$pdf = "../../" . $contrato['pdf_path'];

if(!file_exists($pdf)){
    echo "Archivo PDF no existe";
    exit;
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=contrato.pdf");

readfile($pdf);
exit;
