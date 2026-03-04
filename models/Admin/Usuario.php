<?php
// models/Admin/Usuario.php
require_once __DIR__ . '/../../config/db.php';

class Usuario
{
    public static function findByEmail($correo)
    {
        $db = Database::connect();
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM tbUsuarios u
                LEFT JOIN tbRoles r ON u.id_rol = r.id_rol
                WHERE u.correo = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $db = Database::connect();
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM tbUsuarios u
                LEFT JOIN tbRoles r ON u.id_rol = r.id_rol
                WHERE u.id_usuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Otros métodos que puedas tener...
}