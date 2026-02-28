<?php
// controller/Empleado/ClientesEmpleadoController.php
require_once __DIR__ . '/BaseEmpleadoController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';

class ClientesEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        parent::__construct();
        $clientes = Cliente::all();
        $totalClientes = Cliente::count();
        $activos = Cliente::countActivos();
        $nuevosMes = Cliente::countNuevosMes();
        $inactivos = $totalClientes - $activos;

        $titulo = 'Gestión de Clientes';
        $seccion = 'clientes';
        require PROJECT_ROOT_FS . '/views/empleado/clientes/index.php';
    }

    public function nuevo()
    {
        parent::__construct();
        $titulo = 'Nuevo Cliente';
        $seccion = 'clientes';
        require PROJECT_ROOT_FS . '/views/empleado/clientes/nuevo.php';
    }

    public function crear()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $data = [
            'nombre'    => $_POST['nombre'],
            'apellido'  => $_POST['apellido'],
            'DUI'       => $_POST['dui'],
            'direccion' => $_POST['direccion'] ?? null,
            'telefono'  => $_POST['telefono'] ?? null,
            'correo'    => $_POST['email'] ?? null,
        ];

        if (Cliente::create($data)) {
            header('Location: ' . url('index.php?area=empleado&controller=ClientesEmpleado&action=index&success=creado'));
        } else {
            header('Location: ' . url('index.php?area=empleado&controller=ClientesEmpleado&action=index&error=crear'));
        }
        exit;
    }

    public function ver()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $cliente = Cliente::find($id);
        header('Content-Type: application/json');
        echo json_encode($cliente);
        exit;
    }
}