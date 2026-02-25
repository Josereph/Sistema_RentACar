<?php
require_once __DIR__ . '/../../config/db.php';

class Cliente
{
    public static function all()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM tbClientes ORDER BY id_cliente DESC");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbClientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = Database::connect();
        $sql = "INSERT INTO tbClientes (nombre, apellido, DUI, direccion, telefono, correo)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['DUI'],
            $data['direccion'] ?? null,
            $data['telefono'] ?? null,
            $data['correo'] ?? null
        ]);
    }

    public static function update($id, $data)
    {
        $db = Database::connect();
        $sql = "UPDATE tbClientes SET
                nombre = ?, apellido = ?, DUI = ?, direccion = ?, telefono = ?, correo = ?
                WHERE id_cliente = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['DUI'],
            $data['direccion'] ?? null,
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tbClientes WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    }

    public static function count()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbClientes")->fetchColumn();
    }

    public static function countActivos()
    {
        // No hay campo estado en tbClientes, pero podemos considerar activos si tienen reservas recientes
        // Por simplicidad, devolvemos un número aleatorio o lo calculamos con subconsulta
        $db = Database::connect();
        return $db->query("SELECT COUNT(DISTINCT id_cliente) FROM tbReservas WHERE fecha_entrega >= CURDATE()")->fetchColumn();
    }

    public static function countNuevosMes()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbClientes WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();
    }
}