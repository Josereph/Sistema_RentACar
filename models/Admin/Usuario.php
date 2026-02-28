<?php
// models/Admin/Usuario.php
require_once __DIR__ . '/../../config/db.php';

class Usuario
{
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

   public static function findById($id)
{
    $db = Database::connect();
    $sql = "SELECT u.*, r.nombre AS rol_nombre
            FROM tbUsuarios u
            INNER JOIN tbRoles r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve array o false
}

    public static function findByOAuth($provider, $uid)
    {
        $db = Database::connect();
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM tbUsuarios u
                INNER JOIN tbRoles r ON u.id_rol = r.id_rol
                WHERE u.oauth_provider = ? AND u.oauth_uid = ? AND u.estado = 'activo'";
        $stmt = $db->prepare($sql);
        $stmt->execute([$provider, $uid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createFromGoogle($data)
    {
        $db = Database::connect();
        $rol = $db->query("SELECT id_rol FROM tbRoles WHERE nombre = 'operador'")->fetchColumn();
        if (!$rol) {
            $rol = 3;
        }

        $sql = "INSERT INTO tbUsuarios (id_rol, nombre, correo, imagen, oauth_provider, oauth_uid, estado)
                VALUES (?, ?, ?, ?, ?, ?, 'activo')";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $rol,
            $data['nombre'],
            $data['correo'],
            $data['imagen'] ?? null,
            $data['provider'],
            $data['uid']
        ]);
        return $db->lastInsertId();
    }

    public static function verifyPassword($plain, $hash)
    {
        return password_verify($plain, $hash);
    }

    public static function create($nombre, $correo, $password, $rol = null)
    {
        $db = Database::connect();
        if (!$rol) {
            $rol = $db->query("SELECT id_rol FROM tbRoles WHERE nombre = 'operador'")->fetchColumn() ?: 3;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbUsuarios (id_rol, nombre, correo, password_hash, estado)
                VALUES (?, ?, ?, ?, 'activo')";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$rol, $nombre, $correo, $hash]);
    }
}