<?php
require_once __DIR__ . '/../../config/db.php';

class Multa
{
    public static function allPendientes()
    {
        $db = Database::connect();
        $sql = "SELECT m.*, r.id_reserva, c.nombre, c.apellido, v.marca, v.modelo, v.numero_placa
                FROM tbMultas m
                INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE m.pagada = 0
                ORDER BY m.created_at DESC";
        return $db->query($sql)->fetchAll();
    }

    public static function pagar($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tbMultas SET pagada = 1 WHERE id_multa = ?");
        return $stmt->execute([$id]);
    }
}