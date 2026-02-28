<?php
// controller/Empleado/ReservasEmpleadoController.php
require_once __DIR__ . '/BaseEmpleadoController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Contrato.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Pago.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';
require_once __DIR__ . '/../../helpers/EmailHelper.php';

class ReservasEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        parent::__construct();
        $db = Database::connect();
        $reservas = $db->query("
            SELECT r.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                   v.marca, v.modelo, v.numero_placa
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            ORDER BY r.fecha_reserva DESC
        ")->fetchAll();

        $titulo = 'Reservas';
        $seccion = 'reservas';
        require PROJECT_ROOT_FS . '/views/empleado/reservas/index.php';
    }

    public function nueva()
    {
        parent::__construct();
        $clientes = Cliente::all();
        $vehiculos = Vehiculo::all();
        $titulo = 'Nueva Reserva';
        $seccion = 'reservas';
        require PROJECT_ROOT_FS . '/views/empleado/reservas/nueva.php';
    }

    public function crear()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id_cliente = $_POST['id_cliente'];
        $id_vehiculo = $_POST['id_vehiculo'];
        $fecha_recogida = $_POST['fecha_recogida'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $deposito = floatval($_POST['deposito'] ?? 0);
        $metodo_pago = $_POST['metodo_pago'] ?? null;

        $vehiculo = Vehiculo::find($id_vehiculo);
        $dias = (strtotime($fecha_entrega) - strtotime($fecha_recogida)) / 86400;
        $precio_total = $dias * $vehiculo['precio_dia'];

        if (!Reserva::disponible($id_vehiculo, $fecha_recogida, $fecha_entrega)) {
            die('El vehículo no está disponible en esas fechas');
        }

        try {
            $db = Database::connect();
            $db->beginTransaction();

            $id_reserva = Reserva::create([
                'id_cliente' => $id_cliente,
                'id_vehiculo' => $id_vehiculo,
                'fecha_recogida' => $fecha_recogida,
                'fecha_entrega' => $fecha_entrega,
                'precio_total' => $precio_total,
                'estado' => 'confirmada'
            ]);

            $numero_contrato = Contrato::generarNumero();

            Contrato::create([
                'id_reserva' => $id_reserva,
                'numero_contrato' => $numero_contrato,
                'fecha_contrato' => date('Y-m-d'),
                'terminos' => 'Términos y condiciones estándar',
                'deposito' => $deposito,
                'estado' => 'activo'
            ]);

            if ($deposito > 0 && $metodo_pago) {
                Pago::create($id_reserva, $deposito, $metodo_pago, [
                    ['concepto' => 'deposito', 'monto' => $deposito, 'descripcion' => 'Depósito de reserva']
                ]);
            }

            $db->commit();

            // Enviar correo de confirmación
            $cliente = Cliente::find($id_cliente);
            $datosCorreo = [
                'vehiculo' => $vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (' . $vehiculo['numero_placa'] . ')',
                'fecha_recogida' => date('d/m/Y', strtotime($fecha_recogida)),
                'fecha_entrega' => date('d/m/Y', strtotime($fecha_entrega)),
                'total' => number_format($precio_total, 2)
            ];
            EmailHelper::sendReservaConfirmada($cliente['correo'], $cliente['nombre'], $datosCorreo);

            header('Location: ' . url('index.php?area=empleado&controller=ReservasEmpleado&action=index&success=creada'));
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            die('Error al crear la reserva: ' . $e->getMessage());
        }
    }

    public function verificarDisponibilidad()
    {
        parent::__construct();
        $id_vehiculo = $_GET['id_vehiculo'];
        $fecha_inicio = $_GET['fecha_inicio'];
        $fecha_fin = $_GET['fecha_fin'];
        $excluir = $_GET['excluir'] ?? 0;
        $disponible = Reserva::disponible($id_vehiculo, $fecha_inicio, $fecha_fin, $excluir);
        echo json_encode(['disponible' => $disponible]);
        exit;
    }

    // Opcional: ver detalle de reserva (solo lectura)
    public function ver()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT r.*, c.nombre, c.apellido, c.correo, c.telefono,
                   v.marca, v.modelo, v.numero_placa, v.precio_dia,
                   ct.numero_contrato, ct.deposito, ct.pdf_path
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN tbContratos ct ON r.id_reserva = ct.id_reserva
            WHERE r.id_reserva = ?
        ");
        $stmt->execute([$id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($reserva);
        exit;
    }
}