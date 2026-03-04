<?php
class BaseAdminController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
            // Si es AJAX, responder con JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(['error' => 'Sesión expirada']);
                exit;
            }
            header('Location: /Sistema_RentACar/index.php?area=admin&controller=Auth&action=login');
            exit;
        }
    }

    protected function checkRol($rolesPermitidos)
    {
        if (!in_array($_SESSION['admin_rol'], $rolesPermitidos)) {
            die("Acceso denegado: no tienes permisos suficientes.");
        }
    }
}