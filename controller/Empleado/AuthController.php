<?php
// controller/Empleado/AuthController.php
require_once __DIR__ . '/BaseEmpleadoController.php';

class AuthController extends BaseEmpleadoController
{
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /Sistema_RentACar/index.php?area=admin&controller=Auth&action=login');
        exit;
    }
}