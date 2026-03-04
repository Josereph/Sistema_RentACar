<?php
// controller/Empleado/BaseEmpleadoController.php
class BaseEmpleadoController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar que el usuario sea empleado (rol 'operador')
        if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true || $_SESSION['admin_rol'] !== 'operador') {
            header('Location: /Sistema_RentACar/index.php?area=admin&controller=Auth&action=login');
            exit;
        }
    }
}