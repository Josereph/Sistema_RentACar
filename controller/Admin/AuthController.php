<?php
// controller/Admin/AuthController.php
require_once __DIR__ . '/../../models/Admin/Usuario.php';

class AuthController
{
    public function login()
    {
        if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
            $this->redirectByRole();
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = Usuario::findByEmail($correo);

            if ($usuario && Usuario::verifyPassword($password, $usuario['password_hash'])) {
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id'] = $usuario['id_usuario'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];
                $_SESSION['admin_rol'] = $usuario['rol_nombre'];

                $this->redirectByRole();
                exit;
            } else {
                $error = "Credenciales incorrectas o usuario inactivo.";
            }
        }

        require __DIR__ . '/../../views/admin/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . url('index.php?controller=Auth&action=login'));
        exit;
    }

    private function redirectByRole()
    {
        $rol = $_SESSION['admin_rol'] ?? '';
        if ($rol === 'operador') {
            header('Location: ' . url('index.php?area=empleado&controller=DashboardEmpleado&action=index'));
        } else {
            // admin o cualquier otro
            header('Location: ' . url('index.php?controller=Dashboard&action=index'));
        }
    }
}