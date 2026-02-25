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
}