<?php
// controller/Admin/AuthController.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ClienteModel.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $db = Database::connect();

            // 1. Intentar como usuario del sistema (admin/empleado)
            $sql = "SELECT u.*, r.nombre as rol_nombre 
                    FROM tbUsuarios u
                    LEFT JOIN tbRoles r ON u.id_rol = r.id_rol
                    WHERE u.correo = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                session_start();
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id'] = $usuario['id_usuario'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];
                $_SESSION['admin_rol'] = $usuario['rol_nombre'];

                // Redirigir según rol
                if ($usuario['rol_nombre'] === 'operador') {
                    header('Location: /Sistema_RentACar/index.php?area=empleado&controller=DashboardEmpleado&action=index');
                } else {
                    header('Location: /Sistema_RentACar/index.php?area=admin&controller=Dashboard&action=index');
                }
                exit;
            }

            // 2. Intentar como cliente
            $clienteModel = new ClienteModel();
            $cliente = $clienteModel->autenticar($correo, $password);

            if ($cliente) {
                session_start();
                $_SESSION['cliente_logged'] = true;
                $_SESSION['cliente_id'] = $cliente['id_cliente'];
                $_SESSION['cliente_nombre'] = $cliente['nombre'] . ' ' . $cliente['apellido'];
                header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Catalogo&action=index');
                exit;
            }

            // Si no coincide
            die('Credenciales incorrectas');
        } else {
            // Mostrar formulario de login (el mismo)
            include __DIR__ . '/../../views/admin/auth/login.php';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /Sistema_RentACar/index.php?area=admin&controller=Auth&action=login');
        exit;
    }
}