<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Contrato.php';

class ContratosController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $contratos = Contrato::all();
        $titulo = 'Gestión de Contratos';
        $seccion = 'contratos';
        require PROJECT_ROOT_FS . '/views/admin/contratos/index.php';
    }

    public function verPdf()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $ruta = Contrato::getPdf($id);
        if ($ruta && file_exists(PROJECT_ROOT_FS . '/' . $ruta)) {
            header('Content-Type: application/pdf');
            readfile(PROJECT_ROOT_FS . '/' . $ruta);
        } else {
            die('PDF no encontrado');
        }
        exit;
    }

    // controller/Admin/ContratosController.php

public function generarPdf()
{
    parent::__construct();
    $id = $_GET['id'] ?? 0;
    if (!$id) {
        die('ID de contrato no proporcionado');
    }

    require_once __DIR__ . '/../../vendor/autoload.php'; // Asegurar autoload de mPDF

    $contrato = Contrato::find($id);
    if (!$contrato) {
        die('Contrato no encontrado');
    }

    // Obtener datos relacionados
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

    // Generar HTML del contrato
    $html = $this->renderContratoPdf($contrato, $datos);

    // Crear PDF
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_left' => 20,
        'margin_right' => 20
    ]);
    $mpdf->WriteHTML($html);

    // Guardar archivo
    $nombreArchivo = 'contrato_' . $contrato['numero_contrato'] . '.pdf';
    $rutaRelativa = 'storage/pdf/' . $nombreArchivo;
    $rutaAbsoluta = PROJECT_ROOT_FS . '/' . $rutaRelativa;
    $mpdf->Output($rutaAbsoluta, 'F'); // Guardar en archivo

    // Actualizar ruta en la base de datos
    Contrato::updatePdf($id, $rutaRelativa);

    // Redirigir o mostrar mensaje
    header('Location: ' . url('index.php?controller=Contratos&action=index&success=pdf_generado'));
    exit;
}

private function renderContratoPdf($contrato, $datos)
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