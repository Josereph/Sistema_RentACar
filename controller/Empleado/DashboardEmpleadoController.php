<?php
// controller/Empleado/DashboardEmpleadoController.php
require_once __DIR__ . '/BaseEmpleadoController.php';

class DashboardEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        // Lógica del dashboard
        $titulo = 'Dashboard Empleado';
        $seccion = 'dashboard';
        require PROJECT_ROOT_FS . '/views/empleado/dashboard/index.php';
    }
}