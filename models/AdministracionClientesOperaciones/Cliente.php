<?php
require_once __DIR__ . '/../../config/db.php';

class Cliente
{
    /**
     * Obtener todos los clientes ordenados por ID descendente
     */
    public static function all()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM tbClientes ORDER BY id_cliente DESC");
        return $stmt->fetchAll();
    }

    /**
     * Buscar cliente por ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbClientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Crear un nuevo cliente
     */
    public static function create($data)
    {
        $db = Database::connect();
        $sql = "INSERT INTO tbClientes (nombre, apellido, DUI, telefono, correo, direccion)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['DUI'],
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['direccion'] ?? null
        ]);
    }

    /**
     * Actualizar datos de un cliente
     */
    public static function update($id, $data)
    {
        $db = Database::connect();
        $sql = "UPDATE tbClientes SET
                nombre = ?,
                apellido = ?,
                DUI = ?,
                telefono = ?,
                correo = ?,
                direccion = ?
                WHERE id_cliente = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['DUI'],
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['direccion'] ?? null,
            $id
        ]);
    }

    /**
     * Eliminar cliente por ID
     */
    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tbClientes WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Contar total de clientes
     */
    public static function count()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbClientes")->fetchColumn();
    }

    /**
     * Contar clientes con reservas en curso (considerados activos)
     */
    public static function countActivos()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(DISTINCT id_cliente) FROM tbReservas WHERE estado = 'en_curso'")->fetchColumn();
    }

    /**
     * Contar clientes registrados en el mes actual
     */
    public static function countNuevosMes()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbClientes WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();
    }
}