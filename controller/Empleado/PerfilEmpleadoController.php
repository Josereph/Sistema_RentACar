<?php
// controller/Empleado/PerfilEmpleadoController.php
require_once __DIR__ . '/BaseEmpleadoController.php';

class PerfilEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        $titulo = 'Mi Perfil';
        $seccion = 'perfil';
        require PROJECT_ROOT_FS . '/views/empleado/perfil/index.php';
    }
}