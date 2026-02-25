<?php
require_once __DIR__ . '/../../config/db.php';

class Reserva
{
    public static function all()
    {
    $db = Database::connect();
    $sql = "SELECT r.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido, 
                   v.marca, v.modelo, v.numero_placa,
                   ct.id_contrato, ct.numero_contrato
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN tbContratos ct ON r.id_reserva = ct.id_reserva
            ORDER BY r.fecha_reserva DESC";
    return $db->query($sql)->fetchAll();
}

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbReservas WHERE id_reserva = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = Database::connect();
        $sql = "INSERT INTO tbReservas (id_cliente, id_vehiculo, id_usuario, fecha_recogida, fecha_entrega, precio_total, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $data['id_cliente'],
            $data['id_vehiculo'],
            $data['id_usuario'] ?? $_SESSION['admin_id'],
            $data['fecha_recogida'],
            $data['fecha_entrega'],
            $data['precio_total'],
            $data['estado'] ?? 'pendiente'
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data)
    {
        $db = Database::connect();
        $sql = "UPDATE tbReservas SET 
                id_cliente = ?, id_vehiculo = ?, fecha_recogida = ?, fecha_entrega = ?, precio_total = ?, estado = ?
                WHERE id_reserva = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['id_cliente'],
            $data['id_vehiculo'],
            $data['fecha_recogida'],
            $data['fecha_entrega'],
            $data['precio_total'],
            $data['estado'],
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tbReservas WHERE id_reserva = ?");
        return $stmt->execute([$id]);
    }

    // Verificar disponibilidad del vehículo en un rango de fechas
    public static function disponible($id_vehiculo, $fecha_inicio, $fecha_fin, $excluir_reserva = 0)
    {
        $db = Database::connect();
        $sql = "SELECT COUNT(*) FROM tbReservas 
                WHERE id_vehiculo = ? 
                AND id_reserva != ? 
                AND estado IN ('pendiente', 'confirmada', 'en_curso')
                AND (fecha_recogida <= ? AND fecha_entrega >= ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_vehiculo, $excluir_reserva, $fecha_fin, $fecha_inicio]);
        return $stmt->fetchColumn() == 0;
    }

    // Obtener reservas para calendario (usado en VehiculosController)
    public static function allEntreFechas($start, $end)
    {
        $db = Database::connect();
        $sql = "SELECT r.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                       v.marca, v.modelo, v.numero_placa
                FROM tbReservas r
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE r.fecha_recogida <= ? AND r.fecha_entrega >= ?
                ORDER BY r.fecha_recogida";
        $stmt = $db->prepare($sql);
        $stmt->execute([$end, $start]);
        return $stmt->fetchAll();
    }

    
}