<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Devolucion.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';

class DevolucionController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        require PROJECT_ROOT_FS . '/views/admin/devoluciones/index.php';
    }

    public function buscarContrato()
    {
        parent::__construct();
        $noContrato = $_GET['no'] ?? '';
        $reserva = Devolucion::buscarPorContrato($noContrato);
        if ($reserva) {
            echo json_encode([
                'success' => true,
                'cliente' => $reserva['nombre'] . ' ' . $reserva['apellido'],
                'cliente_id' => $reserva['id_cliente'],
                'vehiculo' => $reserva['marca'] . ' ' . $reserva['modelo'],
                'vehiculo_id' => $reserva['id_vehiculo'],
                'placa' => $reserva['numero_placa'],
                'fecha_salida' => $reserva['fecha_recogida'],
                'fecha_pactada' => $reserva['fecha_entrega'],
                'precio_dia' => $reserva['precio_dia'],
                'id_reserva' => $reserva['id_reserva']
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function registrar()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id_reserva = $_POST['id_reserva'] ?? 0;
        if (!$id_reserva) {
            die('Reserva no identificada');
        }

        // Obtener datos de la reserva para cálculos
        $db = Database::connect();
        $reserva = Reserva::find($id_reserva);
        $vehiculo = Vehiculo::find($reserva['id_vehiculo']);

        // Datos del formulario
        $fecha_real = $_POST['fecha_devolucion_real'];
        $km_final = (int)$_POST['km_retorno'];
        $combustible_retorno = $_POST['combustible_retorno'];
        $danos_observados = $_POST['danos_observados'] ?? '';
        $cargo_danos = floatval($_POST['cargo_danos'] ?? 0);
        $estado_general = $_POST['estado_general'] ?? 'bueno';
        $observaciones_finales = $_POST['observaciones_finales'] ?? '';

        // Calcular días de atraso
        $fecha_pactada = new DateTime($reserva['fecha_entrega']);
        $fecha_real_dt = new DateTime($fecha_real);
        $dias_atraso = 0;
        if ($fecha_real_dt > $fecha_pactada) {
            $dias_atraso = $fecha_pactada->diff($fecha_real_dt)->days;
        }

        // Cargo por atraso (50% extra por día)
        $cargo_atraso = $dias_atraso * $vehiculo['precio_dia'] * 0.5;

        // Cargo por combustible (asumiendo que salió lleno)
        $nivel_combustible_salida = 'lleno'; // idealmente vendría de la reserva
        $costo_combustible_por_nivel = [
            'lleno' => 0,
            'tres_cuartos' => 10,
            'medio' => 20,
            'cuarto' => 30,
            'vacio' => 50
        ];
        $cargo_combustible = 0;
        if ($combustible_retorno !== $nivel_combustible_salida) {
            $cargo_combustible = $costo_combustible_por_nivel[$combustible_retorno] ?? 0;
        }

        // Armar array de multas
        $multas = [];
        if ($cargo_atraso > 0) {
            $multas[] = [
                'tipo' => 'tarde',
                'monto' => $cargo_atraso,
                'motivo' => "Atraso de $dias_atraso día(s) en la devolución"
            ];
        }
        if ($cargo_combustible > 0) {
            $multas[] = [
                'tipo' => 'otro',
                'monto' => $cargo_combustible,
                'motivo' => "Combustible inferior al nivel de salida ($combustible_retorno)"
            ];
        }
        if ($cargo_danos > 0) {
            $multas[] = [
                'tipo' => 'danio',
                'monto' => $cargo_danos,
                'motivo' => $danos_observados ?: 'Daños observados'
            ];
        }

        $data = [
            'fecha_real'    => $fecha_real,
            'km_final'      => $km_final,
            'combustible'   => $combustible_retorno,
            'observaciones' => $danos_observados . "\n" . $observaciones_finales,
            'estado'        => $estado_general == 'malo' ? 'observado' : 'ok',
            'multas'        => $multas
        ];

        try {
            $id_devolucion = Devolucion::registrarDevolucion($id_reserva, $data);
            // Redirigir al checklist con el id_devolucion generado
            header('Location: /Sistema_RentACar/index.php?controller=Checklist&action=index&id_devolucion=' . $id_devolucion);
            exit;
        } catch (Exception $e) {
            header('Location: ' . url('index.php?controller=Devolucion&action=index&error=1&msg=' . urlencode($e->getMessage())));
            exit;
        }
    }
}