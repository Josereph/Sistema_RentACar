<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Mantenimiento.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';
require_once __DIR__ . '/../../helpers/EmailHelper.php'; // AÑADIDO

class MantenimientosController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $mantenimientos = Mantenimiento::all();
        $titulo = 'Gestión de Mantenimientos';
        $seccion = 'mantenimientos';
        require PROJECT_ROOT_FS . '/views/admin/mantenimientos/index.php';
    }

    public function nuevo()
    {
        parent::__construct();
        $vehiculos = Vehiculo::all();
        $titulo = 'Nuevo Mantenimiento';
        $seccion = 'mantenimientos';
        require PROJECT_ROOT_FS . '/views/admin/mantenimientos/form.php';
    }

    public function crear()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $data = [
            'id_vehiculo' => $_POST['id_vehiculo'],
            'tipo' => $_POST['tipo'],
            'descripcion' => $_POST['descripcion'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'] ?: null,
            'costo' => $_POST['costo'] ?: null,
            'km_actual' => $_POST['km_actual'] ?: null,
            'estado' => $_POST['estado']
        ];

        Mantenimiento::create($data);

        // Opcional: notificar a clientes con reservas en ese vehículo (próximas)
        // (Implementar según necesidad)

        header('Location: /Sistema_RentACar/index.php?controller=Mantenimientos&action=index&success=creado');
        exit;
    }

    public function editar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $mantenimiento = Mantenimiento::find($id);
        if (!$mantenimiento) {
            die('Mantenimiento no encontrado');
        }
        $vehiculos = Vehiculo::all();
        $titulo = 'Editar Mantenimiento';
        $seccion = 'mantenimientos';
        require PROJECT_ROOT_FS . '/views/admin/mantenimientos/form.php';
    }

    public function actualizar()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id = $_POST['id'];
        $data = [
            'id_vehiculo' => $_POST['id_vehiculo'],
            'tipo' => $_POST['tipo'],
            'descripcion' => $_POST['descripcion'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'] ?: null,
            'costo' => $_POST['costo'] ?: null,
            'km_actual' => $_POST['km_actual'] ?: null,
            'estado' => $_POST['estado']
        ];

        Mantenimiento::update($id, $data);
        header('Location: /Sistema_RentACar/index.php?controller=Mantenimientos&action=index&success=editado');
        exit;
    }

    public function eliminar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            Mantenimiento::delete($id);
        }
        header('Location: /Sistema_RentACar/index.php?controller=Mantenimientos&action=index&success=eliminado');
        exit;
    }

    public function finalizar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $mantenimiento = Mantenimiento::find($id);
            if ($mantenimiento) {
                $data = [
                    'id_vehiculo' => $mantenimiento['id_vehiculo'],
                    'tipo' => $mantenimiento['tipo'],
                    'descripcion' => $mantenimiento['descripcion'],
                    'fecha_inicio' => $mantenimiento['fecha_inicio'],
                    'fecha_fin' => date('Y-m-d'),
                    'costo' => $mantenimiento['costo'],
                    'km_actual' => $mantenimiento['km_actual'],
                    'estado' => 'finalizado'
                ];
                Mantenimiento::update($id, $data);
            }
        }
        header('Location: /Sistema_RentACar/index.php?controller=Mantenimientos&action=index&success=finalizado');
        exit;
    }
}