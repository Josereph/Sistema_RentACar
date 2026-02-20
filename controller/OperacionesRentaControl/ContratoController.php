<?php

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

class ContratoController {

    private $conexion;

    public function __construct() {
        global $conexion;
        $this->conexion = $conexion;
    }

    public function generarContrato($id_reserva) {

        // ==============================
        // 1. OBTENER DATOS DE LA RESERVA
        // ==============================

        $sql = "SELECT 
                    r.id_reserva,
                    r.fecha_recogida,
                    r.fecha_entrega,
                    r.precio_total,

                    c.nombre,
                    c.apellido,
                    c.DUI,
                    c.direccion,
                    c.telefono,
                    c.correo,

                    v.car_name,
                    v.marca,
                    v.modelo,
                    v.numero_placa,
                    v.color,
                    v.year

                FROM Reservas r
                INNER JOIN Clientes c ON r.id_cliente = c.id_cliente
                INNER JOIN Vehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE r.id_reserva = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $data = $resultado->fetch_assoc();

        if(!$data){
            return false;
        }

        // ==============================
        // 2. CREAR PDF
        // ==============================

        $mpdf = new Mpdf();

        $html = "
        <h1>Contrato de Renta</h1>

        <h3>Datos del Cliente</h3>
        <p>Nombre: {$data['nombre']} {$data['apellido']}</p>
        <p>DUI: {$data['DUI']}</p>
        <p>Dirección: {$data['direccion']}</p>
        <p>Teléfono: {$data['telefono']}</p>

        <h3>Datos del Vehículo</h3>
        <p>Vehículo: {$data['marca']} {$data['car_name']}</p>
        <p>Modelo: {$data['modelo']} - {$data['year']}</p>
        <p>Placa: {$data['numero_placa']}</p>
        <p>Color: {$data['color']}</p>

        <h3>Datos de la Reserva</h3>
        <p>Fecha Recogida: {$data['fecha_recogida']}</p>
        <p>Fecha Entrega: {$data['fecha_entrega']}</p>
        <p>Total: $ {$data['precio_total']}</p>
        ";

        $mpdf->WriteHTML($html);

        // ==============================
        // 3. GUARDAR PDF
        // ==============================

        $numeroContrato = "CTR-" . time();

        $ruta = "contratosPDF/" . $numeroContrato . ".pdf";

        $mpdf->Output(__DIR__ . "/../" . $ruta, "F");

        // ==============================
        // 4. INSERTAR EN TABLA CONTRATOS
        // ==============================

        $sqlInsert = "INSERT INTO Contratos
            (id_reserva, numero_contrato, fecha_contrato, terminos, deposito, estado, pdf_path)
            VALUES (?, ?, NOW(), 'Contrato generado automáticamente', 0, 'activo', ?)";

        $stmtInsert = $this->conexion->prepare($sqlInsert);
        $stmtInsert->bind_param("iss", $id_reserva, $numeroContrato, $ruta);
        $stmtInsert->execute();

        return $ruta;
    }

}
