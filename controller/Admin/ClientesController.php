<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';

class ClientesController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();

        $clientes = Cliente::all();
        $totalClientes = Cliente::count();
        $activos = Cliente::countActivos();
        $nuevosMes = Cliente::countNuevosMes();
        $inactivos = $totalClientes - $activos;

        require __DIR__ . '/../../views/admin/clientes/index.php'; // <-- CORREGIDO
    }

    public function crear()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'    => $_POST['nombre'],
                'apellido'  => $_POST['apellido'],
                'DUI'       => $_POST['dui'],
                'direccion' => $_POST['direccion'] ?? null,
                'telefono'  => $_POST['telefono'] ?? null,
                'correo'    => $_POST['email'] ?? null,
            ];
            if (Cliente::create($data)) {
                header('Location: index.php?controller=Clientes&action=index&success=creado');
            } else {
                header('Location: index.php?controller=Clientes&action=index&error=crear');
            }
        }
    }

    public function editar()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'nombre'    => $_POST['nombre'],
                'apellido'  => $_POST['apellido'],
                'DUI'       => $_POST['dui'],
                'direccion' => $_POST['direccion'] ?? null,
                'telefono'  => $_POST['telefono'] ?? null,
                'correo'    => $_POST['email'] ?? null,
            ];
            if (Cliente::update($id, $data)) {
                header('Location: index.php?controller=Clientes&action=index&success=editado');
            } else {
                header('Location: index.php?controller=Clientes&action=index&error=editar');
            }
        }
    }

    public function eliminar()
    {
        parent::__construct();

        $id = $_GET['id'] ?? 0;
        if ($id) {
            Cliente::delete($id);
        }
        header('Location: index.php?controller=Clientes&action=index');
    }

    public function getJson()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $cliente = Cliente::find($id);
        header('Content-Type: application/json');
        echo json_encode($cliente);
        exit;
    }
}