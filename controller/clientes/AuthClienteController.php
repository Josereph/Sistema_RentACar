<?php
// controller/Cliente/AuthClienteController.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Admin/Usuario.php'; // reutilizamos el modelo

class AuthClienteController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Si ya está logueado como cliente, redirigir al catálogo
        if (isset($_SESSION['cliente_logged']) && $_SESSION['cliente_logged'] === true) {
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Catalogo&action=index');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuario = Usuario::findByEmail($correo);
            // Verificar que sea cliente (rol = 4 o 'cliente')
            if ($usuario && $usuario['rol_nombre'] === 'cliente' && Usuario::verifyPassword($password, $usuario['password_hash'])) {
                $_SESSION['cliente_logged'] = true;
                $_SESSION['cliente_id'] = $usuario['id_usuario'];
                $_SESSION['cliente_nombre'] = $usuario['nombre'];
                $_SESSION['cliente_email'] = $usuario['correo'];
                header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Catalogo&action=index');
                exit;
            } else {
                $error = "Credenciales incorrectas o no tienes una cuenta de cliente.";
            }
        }

        // Vista de login para cliente
        require PROJECT_ROOT_FS . '/views/cliente/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Auth&action=login');
        exit;
    }

    public function registro()
    {
        // Mostrar formulario de registro
        // Procesar registro, crear usuario con rol cliente
    }
}