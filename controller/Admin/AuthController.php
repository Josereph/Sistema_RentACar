<?php
// controller/Admin/AuthController.php
// NO llamamos a session_start() aquí porque ya se inició en index.php

require_once __DIR__ . '/../../models/Admin/Usuario.php';

class AuthController
{
    public function login()
    {
        // Si ya está logueado, redirigir al dashboard o a clientes
        if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
            header('Location: index.php?controller=Dashboard&action=index');
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
                header('Location: index.php?controller=Dashboard&action=index');
                exit;
            } else {
                $error = "Credenciales incorrectas o usuario inactivo.";
            }
        }

        // Cargar vista de login
        require __DIR__ . '/../../views/admin/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?controller=Auth&action=login');
        exit;
    }
}