<?php
require_once __DIR__ . '/../../config/db.php';

class Mantenimiento
{
    public static function all()
    {
        $db = Database::connect();
        $sql = "SELECT m.*, v.marca, v.modelo, v.numero_placa
                FROM tb_mantenimientos m
                INNER JOIN tbVehiculos v ON m.id_vehiculo = v.id_vehiculo
                ORDER BY m.fecha_inicio DESC";
        return $db->query($sql)->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tb_mantenimientos WHERE id_mantenimiento = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = Database::connect();
        $sql = "INSERT INTO tb_mantenimientos (id_vehiculo, tipo, descripcion, fecha_inicio, fecha_fin, costo, km_actual, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['id_vehiculo'],
            $data['tipo'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_fin'] ?? null,
            $data['costo'] ?? null,
            $data['km_actual'] ?? null,
            $data['estado'] ?? 'programado'
        ]);
    }

    public static function update($id, $data)
    {
        $db = Database::connect();
        $sql = "UPDATE tb_mantenimientos SET
                id_vehiculo = ?, tipo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, costo = ?, km_actual = ?, estado = ?
                WHERE id_mantenimiento = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['id_vehiculo'],
            $data['tipo'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_fin'] ?? null,
            $data['costo'] ?? null,
            $data['km_actual'] ?? null,
            $data['estado'],
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tb_mantenimientos WHERE id_mantenimiento = ?");
        return $stmt->execute([$id]);
    }

    // Obtener mantenimientos de un vehículo específico
    public static function byVehiculo($id_vehiculo)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tb_mantenimientos WHERE id_vehiculo = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$id_vehiculo]);
        return $stmt->fetchAll();
    }

    // Mantenimientos programados o en proceso (próximos)
    public static function proximos($limite = 5)
    {
        $db = Database::connect();
        $sql = "SELECT m.*, v.marca, v.modelo, v.numero_placa
                FROM tb_mantenimientos m
                INNER JOIN tbVehiculos v ON m.id_vehiculo = v.id_vehiculo
                WHERE m.estado IN ('programado', 'en_proceso')
                ORDER BY m.fecha_inicio ASC
                LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}