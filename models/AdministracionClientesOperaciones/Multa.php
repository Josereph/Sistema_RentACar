<?php
require_once __DIR__ . '/../../config/db.php';

class Multa
{
    /**
     * Obtener todas las multas con datos relacionados
     */
    public static function all($pagadas = null)
    {
        $db = Database::connect();
        $sql = "SELECT m.*, r.id_reserva, c.nombre, c.apellido, v.marca, v.modelo, v.numero_placa
                FROM tbMultas m
                INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo";
        if ($pagadas !== null) {
            $sql .= " WHERE m.pagada = " . ($pagadas ? '1' : '0');
        }
        $sql .= " ORDER BY m.created_at DESC";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Marcar multa como pagada
     */
    public static function marcarPagada($id_multa)
    {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tbMultas SET pagada = 1 WHERE id_multa = ?");
        return $stmt->execute([$id_multa]);
    }

    /**
     * Obtener multas pendientes de una reserva
     */
    public static function getPorReserva($id_reserva)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbMultas WHERE id_reserva = ? AND pagada = 0");
        $stmt->execute([$id_reserva]);
        return $stmt->fetchAll();
    }
}