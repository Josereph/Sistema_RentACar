<?php
// controller/Cliente/AuthClienteController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ClienteModel.php';
require_once __DIR__ . '/../../models/Admin/Usuario.php'; // Asegúrate que existe

class AuthClienteController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Sistema_RentACar/views/admin/auth/login.php');
            exit;
        }

        $correo = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? '';

        // 1. Intentar como cliente
        $clienteModel = new ClienteModel();
        $cliente = $clienteModel->autenticar($correo, $password);

        if ($cliente) {
            session_regenerate_id(true);
            $_SESSION['cliente_logged'] = true;
            $_SESSION['cliente_id'] = $cliente['id_cliente'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'] . ' ' . $cliente['apellido'];
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Catalogo&action=index');
            exit;
        }

        // 2. Intentar como usuario (admin/empleado)
        require_once __DIR__ . '/../../models/Admin/Usuario.php';
        $usuario = Usuario::findByEmail($correo);
        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $usuario['id_usuario'];
            $_SESSION['admin_nombre'] = $usuario['nombre'];
            $_SESSION['admin_rol'] = $usuario['rol']; // 'admin' o 'operador'

            // Redirigir según el rol
            if ($usuario['rol'] === 'operador') {
                header('Location: /Sistema_RentACar/index.php?area=empleado&controller=DashboardEmpleado&action=index');
            } else {
                header('Location: /Sistema_RentACar/index.php?area=admin&controller=Dashboard&action=index');
            }
            exit;
        }

        die('Credenciales incorrectas');
    }
}