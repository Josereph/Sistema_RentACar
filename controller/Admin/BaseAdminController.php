<?php
// controller/Admin/BaseAdminController.php

class BaseAdminController
{
    public function __construct()
    {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario está logueado como admin
        if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
            header('Location: index.php?controller=Auth&action=login');
            exit;
        }
    }

    // Método opcional para verificar roles específicos
    protected function checkRol($rolesPermitidos)
    {
        if (!in_array($_SESSION['admin_rol'], $rolesPermitidos)) {
            die("Acceso denegado: no tienes permisos suficientes.");
        }
    }
}