<?php
session_start();
require_once __DIR__ . '/../../../config/db.php';

$pdo = Database::connect();

// Recoger datos
$car_id = $_POST['car_id'] ?? 0;
$ubicacion = $_POST['ubicacion'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';

if (!$car_id || !$ubicacion || !$fecha_inicio || !$fecha_fin) {
    die('Faltan datos obligatorios.');
}

if (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
    die('La fecha de fin debe ser posterior a la fecha de inicio.');
}

// Obtener precio del vehículo
$stmt = $pdo->prepare("SELECT precio_dia FROM tbvehiculos WHERE id_vehiculo = ?");
$stmt->execute([$car_id]);
$vehiculo = $stmt->fetch();
if (!$vehiculo) {
    die('Vehículo no encontrado.');
}
$precio_dia = $vehiculo['precio_dia'];

$inicio = new DateTime($fecha_inicio);
$fin = new DateTime($fecha_fin);
$dias = $inicio->diff($fin)->days;
if ($dias <= 0) $dias = 1;
$precio_total = $precio_dia * $dias;

// Verificar disponibilidad
$sql = "SELECT id_reserva FROM tbReservas 
        WHERE id_vehiculo = ? 
        AND estado IN ('pendiente', 'confirmada', 'en_curso')
        AND (
            (fecha_recogida <= ? AND fecha_entrega >= ?)
            OR (fecha_recogida <= ? AND fecha_entrega >= ?)
            OR (? <= fecha_recogida AND ? >= fecha_recogida)
        )";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $car_id,
    $fecha_fin, $fecha_inicio,
    $fecha_inicio, $fecha_inicio,
    $fecha_inicio, $fecha_inicio
]);
if ($stmt->fetch()) {
    die('El vehículo no está disponible en las fechas seleccionadas.');
}

// Supongamos que el cliente ha iniciado sesión y tenemos su id
// Por ahora, simulamos un cliente fijo (id_cliente = 1)
// En un sistema real, deberías obtener $_SESSION['cliente_id']
$id_cliente = 1; // Cambiar por sesión

// Insertar reserva
$sql = "INSERT INTO tbReservas (id_cliente, id_vehiculo, fecha_recogida, fecha_entrega, precio_total, estado)
        VALUES (?, ?, ?, ?, ?, 'pendiente')";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$id_cliente, $car_id, $fecha_inicio, $fecha_fin, $precio_total])) {
    $id_reserva = $pdo->lastInsertId();
    // Redirigir a la página de pago
    header("Location: pagos.php?reserva_id=" . $id_reserva);
    exit;
} else {
    die('Error al crear la reserva.');
}