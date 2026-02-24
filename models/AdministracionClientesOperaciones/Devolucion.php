<?php
require_once PROJECT_ROOT_FS . '/config/db.php';

class Devolucion
{
    public static function getById(int $id_devolucion): ?array
    {
        $db = Database::connect();

        $sql = "SELECT d.*, r.id_vehiculo, v.numero_placa, v.marca, v.modelo, v.year
                FROM tbDevoluciones d
                INNER JOIN tbReservas r ON r.id_reserva = d.id_reserva
                INNER JOIN tbVehiculos v ON v.id_vehiculo = r.id_vehiculo
                WHERE d.id_devolucion = :id";

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id_devolucion]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}