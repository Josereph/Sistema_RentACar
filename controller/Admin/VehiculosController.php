<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';

class VehiculosController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $vehiculos = Vehiculo::all();
        $titulo = 'Gestión de Vehículos';
        $seccion = 'vehiculos';
        require PROJECT_ROOT_FS . '/views/admin/vehiculos/index.php';
    }

    public function form()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $vehiculo = null;
        if ($id) {
            $vehiculo = Vehiculo::find($id);
        }
        $titulo = $id ? 'Editar Vehículo' : 'Nuevo Vehículo';
        $seccion = 'vehiculos';
        require PROJECT_ROOT_FS . '/views/admin/vehiculos/form.php';
    }

    public function guardar()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id = $_POST['id'] ?? 0;
        $data = [
            'car_name'      => $_POST['car_name'],
            'modelo'        => $_POST['modelo'],
            'year'          => $_POST['year'],
            'marca'         => $_POST['marca'],
            'color'         => $_POST['color'],
            'tipo_vehiculo' => $_POST['tipo_vehiculo'],
            'capacidad'     => $_POST['capacidad'],
            'numero_placa'  => $_POST['numero_placa'],
            'precio_dia'    => $_POST['precio_dia'],
            'descripcion'   => $_POST['descripcion'],
            'estado'        => $_POST['estado']
        ];

        if ($id) {
            Vehiculo::update($id, $data);
            $vehiculo_id = $id;
            $mensaje = 'editado';
        } else {
            $vehiculo_id = Vehiculo::create($data);
            $mensaje = 'creado';
        }

        $this->procesarImagenes($vehiculo_id);

        header('Location: /Sistema_RentACar/index.php?controller=Vehiculos&action=index&success=' . $mensaje);
        exit;
    }

    private function procesarImagenes($vehiculo_id)
    {
        if (empty($_FILES['imagenes']['name'][0])) {
            return;
        }

        $archivos = $_FILES['imagenes'];
        $total = count($archivos['name']);
        if ($total > 4) {
            $_SESSION['error_imagenes'] = 'Máximo 4 imágenes';
            return;
        }

        $carpeta_destino = PROJECT_ROOT_FS . '/assets/img/vehiculos/';
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        $patron = $carpeta_destino . $vehiculo_id . '_*.{jpg,jpeg,png,gif}';
        foreach (glob($patron, GLOB_BRACE) as $archivo) {
            unlink($archivo);
        }

        for ($i = 0; $i < $total; $i++) {
            if ($archivos['error'][$i] == UPLOAD_ERR_OK) {
                $extension = pathinfo($archivos['name'][$i], PATHINFO_EXTENSION);
                $nombre_archivo = $vehiculo_id . '_' . ($i+1) . '.' . $extension;
                $ruta_destino = $carpeta_destino . $nombre_archivo;
                move_uploaded_file($archivos['tmp_name'][$i], $ruta_destino);
            }
        }
    }

    public function eliminar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            Vehiculo::delete($id);
        }
        header('Location: /Sistema_RentACar/index.php?controller=Vehiculos&action=index&success=eliminado');
        exit;
    }

    public function getJson()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $vehiculo = Vehiculo::find($id);
        header('Content-Type: application/json');
        echo json_encode($vehiculo);
        exit;
    }

    public function calendario()
    {
        parent::__construct();
        $titulo = 'Calendario de Disponibilidad';
        $seccion = 'vehiculos';
        require PROJECT_ROOT_FS . '/views/admin/vehiculos/calendario.php';
    }

    public function getEventos()
    {
        parent::__construct();
        $start = $_GET['start'] ?? date('Y-m-d');
        $end = $_GET['end'] ?? date('Y-m-d', strtotime('+1 month'));
        $reservas = Reserva::allEntreFechas($start, $end);
        $eventos = [];
        foreach ($reservas as $r) {
            $eventos[] = [
                'title' => $r['marca'] . ' ' . $r['modelo'] . ' - ' . $r['cliente_nombre'],
                'start' => $r['fecha_recogida'],
                'end'   => date('Y-m-d', strtotime($r['fecha_entrega'] . ' +1 day')),
                'color' => '#137fec',
                'url'   => '/Sistema_RentACar/index.php?controller=Devolucion&action=index&reserva=' . $r['id_reserva']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($eventos);
        exit;
    }
}