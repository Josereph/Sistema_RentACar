

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';

class ClientesController extends BaseAdminController
{
    /**
     * Listado de clientes
     */
    public function index()
    {
        parent::__construct();

        $clientes = Cliente::all();
        $totalClientes = Cliente::count();
        $activos = Cliente::countActivos();
        $nuevosMes = Cliente::countNuevosMes();
        $inactivos = $totalClientes - $activos;

        require PROJECT_ROOT_FS . '/views/admin/clientes/index.php';
    }

    /**
     * Crear cliente (POST)
     */
    public function crear()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'    => trim($_POST['nombre']),
                'apellido'  => trim($_POST['apellido']),
                'DUI'       => trim($_POST['dui']),
                'telefono'  => trim($_POST['telefono'] ?? ''),
                'correo'    => trim($_POST['email'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? '')
            ];

            if (Cliente::create($data)) {
                header('Location: ' . $this->url('index.php?area=admin&controller=Clientes&action=index&success=creado'));
            } else {
                header('Location: ' . $this->url('index.php?area=admin&controller=Clientes&action=index&error=crear'));
            }
            exit;
        }
    }

    /**
     * Editar cliente (POST)
     */
    public function editar()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'nombre'    => trim($_POST['nombre']),
                'apellido'  => trim($_POST['apellido']),
                'DUI'       => trim($_POST['dui']),
                'telefono'  => trim($_POST['telefono'] ?? ''),
                'correo'    => trim($_POST['email'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? '')
            ];

            if (Cliente::update($id, $data)) {
                header('Location: ' . $this->url('index.php?area=admin&controller=Clientes&action=index&success=editado'));
            } else {
                header('Location: ' . $this->url('index.php?area=admin&controller=Clientes&action=index&error=editar'));
            }
            exit;
        }
    }

    /**
     * Eliminar cliente (GET)
     */
    public function eliminar()
    {
        parent::__construct();

        $id = $_GET['id'] ?? 0;
        if ($id) {
            Cliente::delete($id);
        }
        header('Location: ' . $this->url('index.php?area=admin&controller=Clientes&action=index'));
        exit;
    }

    /**
     * Obtener datos de un cliente en JSON (para edición)
     */
    public function getJson()
    {
        parent::__construct();

        $id = $_GET['id'] ?? 0;
        $cliente = Cliente::find($id);
        header('Content-Type: application/json');
        echo json_encode($cliente);
        exit;
    }

    /**
     * Exportar a CSV
     */
    public function exportarExcel()
    {
        parent::__construct();

        $clientes = Cliente::all();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="clientes_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Apellido', 'DUI', 'Teléfono', 'Email', 'Dirección', 'Fecha Registro']);

        foreach ($clientes as $c) {
            fputcsv($output, [
                $c['id_cliente'],
                $c['nombre'],
                $c['apellido'],
                $c['DUI'],
                $c['telefono'],
                $c['correo'],
                $c['direccion'],
                $c['created_at']
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * Función auxiliar para generar URLs absolutas
     */
    private function url($path)
    {
        return '/Sistema_RentACar/' . ltrim($path, '/');
    }
}