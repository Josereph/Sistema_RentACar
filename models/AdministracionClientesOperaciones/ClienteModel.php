<?php
// models/AdministracionClientesOperaciones/ClienteModel.php
require_once __DIR__ . '/../../config/db.php';

class ClienteModel {
    private $conn;

    public function __construct() {
        $db = Database::connect();
        $this->conn = $db;
    }

    /**
     * Obtener cliente por ID
     */
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbClientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener cliente por correo
     */
    public function obtenerPorCorreo($correo) {
        $stmt = $this->conn->prepare("SELECT * FROM tbClientes WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    /**
     * Crear un nuevo cliente (desde Google o registro manual)
     */
    public function crearCliente($nombre, $apellido, $correo, $password = null) {
    if ($password) {
        // Registro con contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbClientes (nombre, apellido, correo, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $apellido, $correo, $password_hash]);
    } else {
        // Registro sin contraseña (Google)
        $sql = "INSERT INTO tbClientes (nombre, apellido, correo) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $apellido, $correo]);
    }
    return $this->conn->lastInsertId();
}

    /**
     * Actualizar datos del perfil (DUI, teléfono, dirección)
     */
    public function actualizarDatos($id_cliente, $dui, $telefono, $direccion) {
        $stmt = $this->conn->prepare("UPDATE tbClientes SET DUI = ?, telefono = ?, direccion = ? WHERE id_cliente = ?");
        return $stmt->execute([$dui, $telefono, $direccion, $id_cliente]);
    }

    /**
     * Verificar si el perfil está completo
     */
    public function perfilCompleto($id_cliente) {
        $cliente = $this->obtenerPorId($id_cliente);
        if (!$cliente) return false;
        return !empty($cliente['DUI']) && !empty($cliente['telefono']) && !empty($cliente['direccion']);
    }

    /**
     * Autenticar cliente con correo y contraseña
     */
    public function autenticar($correo, $password) {
        $cliente = $this->obtenerPorCorreo($correo);
        if ($cliente && password_verify($password, $cliente['password_hash'])) {
            return $cliente;
        }
        return false;
    }
}