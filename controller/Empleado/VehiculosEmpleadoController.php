<?php
// controller/Empleado/VehiculosEmpleadoController.php
require_once __DIR__ . '/BaseEmpleadoController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';

class VehiculosEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        parent::__construct();
        $vehiculos = Vehiculo::all();
        $titulo = 'Gestión de Vehículos';
        $seccion = 'vehiculos';
        require PROJECT_ROOT_FS . '/views/empleado/vehiculos/index.php';
    }

    public function nuevo()
    {
        parent::__construct();
        $titulo = 'Nuevo Vehículo';
        $seccion = 'vehiculos';
        require PROJECT_ROOT_FS . '/views/empleado/vehiculos/form.php';
    }

    public function guardar()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

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

        $vehiculo_id = Vehiculo::create($data);
        $this->procesarImagenes($vehiculo_id);

        header('Location: ' . url('index.php?area=empleado&controller=VehiculosEmpleado&action=index&success=creado'));
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

        // Eliminar imágenes anteriores (por si acaso, pero al crear no hay)
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
}