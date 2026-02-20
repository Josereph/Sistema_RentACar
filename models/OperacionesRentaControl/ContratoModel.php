<?php

require_once __DIR__ . '/../../config/conexion.php';

class ContratoModel {

    private $conn;

    public function __construct() {
        global $conexion;
        $this->conn = $conexion;
    }

    // 🔎 Obtener datos de la reserva para el contrato
    public function obtenerDatosReserva($id_reserva) {

        $sql = "SELECT 
                    r.id_reserva,
                    r.fecha_recogida,
                    r.fecha_entrega,
                    r.precio_total,
                    c.nombre,
                    c.apellido,
                    c.DUI,
                    v.car_name,
                    v.marca,
                    v.modelo,
                    v.numero_placa
                FROM Reservas r
                INNER JOIN Clientes c 
                    ON r.id_cliente = c.id_cliente
                INNER JOIN Vehiculos v 
                    ON r.id_vehiculo = v.id_vehiculo
                WHERE r.id_reserva = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // 💾 Insertar contrato generado
    public function insertarContrato($id_reserva, $numero, $terminos, $deposito, $pdf_path) {

        $sql = "INSERT INTO Contratos 
                (id_reserva, numero_contrato, fecha_contrato, terminos, deposito, estado, pdf_path)
                VALUES (?, ?, NOW(), ?, ?, 'activo', ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issds", 
            $id_reserva, 
            $numero, 
            $terminos, 
            $deposito, 
            $pdf_path
        );

        return $stmt->execute();
    }

    // 📄 Obtener ruta del PDF para visualizar contrato
    public function obtenerPdfContrato($id_reserva){

        $sql = "SELECT pdf_path 
                FROM Contratos 
                WHERE id_reserva = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}

?>
