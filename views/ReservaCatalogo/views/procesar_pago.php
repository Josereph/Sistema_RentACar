<?php
// views/ReservaCatalogo/views/procesar_pago.php
session_start();

// Definir rutas (ajusta si es necesario)
if (!defined('PROJECT_ROOT_FS')) {
    define('PROJECT_ROOT_FS', dirname(__DIR__, 3));
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Sistema_RentACar');
}
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// Activar logs
error_log("=== INICIO procesar_pago.php ===");

// Incluir archivos necesarios
require_once PROJECT_ROOT_FS . '/config/db.php';
require_once PROJECT_ROOT_FS . '/models/AdministracionClientesOperaciones/Contrato.php';
require_once PROJECT_ROOT_FS . '/models/AdministracionClientesOperaciones/Pago.php';
require_once PROJECT_ROOT_FS . '/helpers/ClienteEmailHelper.php'; // Nuevo helper

$pdo = Database::connect();

// Recibir datos del formulario
$reserva_id = $_POST['reserva_id'] ?? 0;
$metodo_pago = $_POST['metodo_pago'] ?? '';
$monto = $_POST['monto'] ?? 0;

error_log("Datos recibidos: reserva_id=$reserva_id, metodo=$metodo_pago, monto=$monto");

// Validar datos
if (!$reserva_id || !$metodo_pago || !$monto) {
    die('Faltan datos para procesar el pago. (reserva_id: ' . $reserva_id . ', metodo: ' . $metodo_pago . ', monto: ' . $monto . ')');
}

if ($monto <= 0) {
    die('El monto a pagar no es válido (debe ser mayor a cero).');
}

// Verificar que la reserva existe y está pendiente
$stmt = $pdo->prepare("SELECT * FROM tbReservas WHERE id_reserva = ? AND estado = 'pendiente'");
$stmt->execute([$reserva_id]);
$reserva = $stmt->fetch();

if (!$reserva) {
    die('La reserva no existe o ya fue procesada.');
}

// Iniciar transacción
$pdo->beginTransaction();

try {
    // Preparar conceptos para el pago
    $conceptos = [
        [
            'concepto' => 'renta',
            'monto' => $monto,
            'descripcion' => 'Pago por reserva #' . $reserva_id
        ]
    ];

    // Crear el pago (esto podría lanzar excepción si falla)
    $id_pago = Pago::create($reserva_id, $monto, $metodo_pago, $conceptos);
    if (!$id_pago) {
        throw new Exception("Error al crear el pago (método Pago::create devolvió false)");
    }
    error_log("Pago creado con ID: $id_pago");

    // Actualizar estado de la reserva a 'confirmada'
    $sql_update = "UPDATE tbReservas SET estado = 'confirmada', updated_at = NOW() WHERE id_reserva = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$reserva_id]);
    error_log("Reserva $reserva_id actualizada a confirmada");

    // --- CREACIÓN AUTOMÁTICA DEL CONTRATO ---
    $deposito = $reserva['precio_total'] * 0.20;
    $numero_contrato = Contrato::generarNumero();
    $dataContrato = [
        'id_reserva' => $reserva_id,
        'numero_contrato' => $numero_contrato,
        'fecha_contrato' => date('Y-m-d'),
        'terminos' => 'Términos y condiciones estándar de alquiler.',
        'deposito' => $deposito,
        'estado' => 'activo'
    ];

    if (Contrato::create($dataContrato)) {
        $id_contrato = $pdo->lastInsertId();
        error_log("Contrato creado ID: $id_contrato");
        // Generar PDF (esto puede fallar, pero no detiene el flujo)
        $pdfGenerado = Contrato::generarPdfPorContrato($id_contrato);
        if (!$pdfGenerado) {
            error_log("No se pudo generar el PDF para el contrato ID: $id_contrato");
        }
    } else {
        error_log("Error al crear el contrato para la reserva ID: $reserva_id");
    }

    // Confirmar transacción
    $pdo->commit();
    error_log("Transacción confirmada");

    // Enviar correo de confirmación (capturamos excepciones para no interrumpir)
    try {
        $correoEnviado = ClienteEmailHelper::enviarConfirmacion($reserva_id);
        if ($correoEnviado) {
            error_log("Correo enviado exitosamente para reserva $reserva_id");
        } else {
            error_log("Fallo el envío de correo para reserva $reserva_id (retornó false)");
        }
    } catch (Exception $e) {
        error_log("Excepción al enviar correo: " . $e->getMessage());
    }

    // Redirigir a página de éxito
    header("Location: pago_exitoso.php?reserva_id=" . $reserva_id);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Error en transacción: " . $e->getMessage());
    die("Error al procesar el pago: " . $e->getMessage());
}