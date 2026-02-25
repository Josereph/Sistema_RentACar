<?php
// models/Admin/Usuario.php
require_once __DIR__ . '/../../config/db.php';

class Usuario
{
    /**
     * Busca un usuario activo por su correo e incluye el nombre del rol.
     */
    public static function findByEmail($correo)
    {
        $db = Database::connect();
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM tbUsuarios u
                INNER JOIN tbRoles r ON u.id_rol = r.id_rol
                WHERE u.correo = ? AND u.estado = 'activo'";
        $stmt = $db->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica una contraseña plana contra su hash.
     */
    public static function verifyPassword($plain, $hash)
    {
        return password_verify($plain, $hash);
    }
}