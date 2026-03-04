<?php
require_once __DIR__ . '/../../config/db.php';

class Contrato
{
    public static function all()
    {
        $db = Database::connect();
        $sql = "SELECT c.*, r.fecha_recogida, r.fecha_entrega, 
                       cl.nombre, cl.apellido, cl.DUI,
                       v.marca, v.modelo, v.numero_placa
                FROM tbContratos c
                INNER JOIN tbReservas r ON c.id_reserva = r.id_reserva
                INNER JOIN tbClientes cl ON r.id_cliente = cl.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                ORDER BY c.fecha_contrato DESC";
        return $db->query($sql)->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbContratos WHERE id_contrato = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getPdf($id)
    {
        $contrato = self::find($id);
        if ($contrato && $contrato['pdf_path']) {
            return $contrato['pdf_path'];
        }
        return null;
    }

    // Generar número de contrato único (ej. RENT-2025-0001)
    public static function generarNumero()
    {
        $db = Database::connect();
        $anio = date('Y');
        $stmt = $db->query("SELECT COUNT(*) FROM tbContratos WHERE YEAR(fecha_contrato) = $anio");
        $count = $stmt->fetchColumn() + 1;
        return 'RENT-' . $anio . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public static function create($data)
    {
        $db = Database::connect();
        $sql = "INSERT INTO tbContratos (id_reserva, numero_contrato, fecha_contrato, terminos, deposito, estado)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['id_reserva'],
            $data['numero_contrato'],
            $data['fecha_contrato'],
            $data['terminos'],
            $data['deposito'],
            $data['estado']
        ]);
    }

    public static function updatePdf($id, $pdf_path)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tbContratos SET pdf_path = ? WHERE id_contrato = ?");
        return $stmt->execute([$pdf_path, $id]);
    }

/**
 * Genera el PDF para un contrato existente y actualiza la ruta en la BD.
 * @param int $id_contrato
 * @return bool
 */
public static function generarPdfPorContrato($id_contrato)
{
    // Incluir autoload de Composer (para mPDF)
    require_once __DIR__ . '/../../vendor/autoload.php';

    $contrato = self::find($id_contrato);
    if (!$contrato) {
        return false;
    }

    $db = Database::connect();
    $stmt = $db->prepare("
        SELECT r.*, c.nombre as cliente_nombre, c.apellido, c.DUI, c.direccion, c.telefono, c.correo,
               v.marca, v.modelo, v.year, v.numero_placa, v.precio_dia
        FROM tbReservas r
        INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
        WHERE r.id_reserva = ?
    ");
    $stmt->execute([$contrato['id_reserva']]);
    $datos = $stmt->fetch();

    if (!$datos) {
        return false;
    }

    $html = self::renderContratoPdf($contrato, $datos);

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_left' => 20,
        'margin_right' => 20
    ]);
    $mpdf->WriteHTML($html);

    // Definir directorio de almacenamiento (raíz del proyecto /storage/pdf/)
    $directorio = dirname(__DIR__, 2) . '/storage/pdf/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombreArchivo = 'contrato_' . $contrato['numero_contrato'] . '.pdf';
    $rutaRelativa = 'storage/pdf/' . $nombreArchivo;
    $rutaAbsoluta = $directorio . $nombreArchivo;
    $mpdf->Output($rutaAbsoluta, 'F');

    // Actualizar ruta en la base de datos
    self::updatePdf($id_contrato, $rutaRelativa);

    return true;
}

/**
 * Genera el HTML del contrato para el PDF.
 * @param array $contrato Datos del contrato
 * @param array $datos Datos relacionados (reserva, cliente, vehículo)
 * @return string
 */
private static function renderContratoPdf($contrato, $datos)
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Contrato de Alquiler</title>
        <style>
            body { font-family: sans-serif; }
            h1 { color: #137fec; text-align: center; }
            .datos { margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; }
            td, th { border: 1px solid #ddd; padding: 8px; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <h1>CONTRATO DE ALQUILER DE VEHÍCULO</h1>
        <p><strong>Número de Contrato:</strong> ' . $contrato['numero_contrato'] . '</p>
        <p><strong>Fecha de Contrato:</strong> ' . date('d/m/Y', strtotime($contrato['fecha_contrato'])) . '</p>
        
        <h2>Datos del Cliente</h2>
        <table>
            <tr><th>Nombre</th><td>' . htmlspecialchars($datos['cliente_nombre'] . ' ' . $datos['apellido']) . '</td></tr>
            <tr><th>DUI</th><td>' . htmlspecialchars($datos['DUI']) . '</td></tr>
            <tr><th>Dirección</th><td>' . htmlspecialchars($datos['direccion']) . '</td></tr>
            <tr><th>Teléfono</th><td>' . htmlspecialchars($datos['telefono']) . '</td></tr>
            <tr><th>Correo</th><td>' . htmlspecialchars($datos['correo']) . '</td></tr>
        </table>

        <h2>Datos del Vehículo</h2>
        <table>
            <tr><th>Marca/Modelo</th><td>' . htmlspecialchars($datos['marca'] . ' ' . $datos['modelo']) . '</td></tr>
            <tr><th>Año</th><td>' . $datos['year'] . '</td></tr>
            <tr><th>Placa</th><td>' . htmlspecialchars($datos['numero_placa']) . '</td></tr>
            <tr><th>Precio por día</th><td>$' . number_format($datos['precio_dia'], 2) . '</td></tr>
        </table>

        <h2>Detalles de la Reserva</h2>
        <table>
            <tr><th>Fecha de Recogida</th><td>' . date('d/m/Y', strtotime($datos['fecha_recogida'])) . '</td></tr>
            <tr><th>Fecha de Entrega</th><td>' . date('d/m/Y', strtotime($datos['fecha_entrega'])) . '</td></tr>
            <tr><th>Total</th><td>$' . number_format($datos['precio_total'], 2) . '</td></tr>
        </table>

        <h2>Depósito</h2>
        <p>$' . number_format($contrato['deposito'], 2) . '</p>

        <p>El cliente declara haber recibido el vehículo en buen estado y acepta los términos y condiciones.</p>
        <br><br>
        <div style="display: flex; justify-content: space-between;">
            <div>_________________________<br>Firma del Cliente</div>
            <div>_________________________<br>Firma del Administrador</div>
        </div>
    </body>
    </html>';
    return $html;
}

}