<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Contrato.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Pago.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';
require_once __DIR__ . '/../../helpers/EmailHelper.php'; // <-- AÑADIDO

class ReservasController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $reservas = Reserva::all();
        $titulo = 'Gestión de Reservas';
        $seccion = 'reservas';
        require PROJECT_ROOT_FS . '/views/admin/reservas/index.php';
    }

    public function nueva()
    {
        parent::__construct();
        $clientes = Cliente::all();
        $vehiculos = Vehiculo::all();
        $titulo = 'Nueva Reserva';
        $seccion = 'reservas';
        require PROJECT_ROOT_FS . '/views/admin/reservas/nueva.php';
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
            $vehiculo = Vehiculo::find($id_vehiculo);
            $datosCorreo = [
                'vehiculo' => $vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (' . $vehiculo['numero_placa'] . ')',
                'fecha_recogida' => date('d/m/Y', strtotime($fecha_recogida)),
                'fecha_entrega' => date('d/m/Y', strtotime($fecha_entrega)),
                'total' => number_format($precio_total, 2)
            ];
            EmailHelper::sendReservaConfirmada($cliente['correo'], $cliente['nombre'], $datosCorreo);

            header('Location: /Sistema_RentACar/index.php?controller=Reservas&action=index&success=creada');
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

    public function editar($id)
    {
        parent::__construct();
        $reserva = Reserva::find($id);
        if (!$reserva) {
            die('Reserva no encontrada');
        }
        $clientes = Cliente::all();
        $vehiculos = Vehiculo::all();
        $titulo = 'Editar Reserva';
        $seccion = 'reservas';
        require PROJECT_ROOT_FS . '/views/admin/reservas/editar.php';
    }

    public function actualizar()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id = $_POST['id'];
        $id_cliente = $_POST['id_cliente'];
        $id_vehiculo = $_POST['id_vehiculo'];
        $fecha_recogida = $_POST['fecha_recogida'];
        $fecha_entrega = $_POST['fecha_entrega'];

        $vehiculo = Vehiculo::find($id_vehiculo);
        $dias = (strtotime($fecha_entrega) - strtotime($fecha_recogida)) / 86400;
        $precio_total = $dias * $vehiculo['precio_dia'];

        if (!Reserva::disponible($id_vehiculo, $fecha_recogida, $fecha_entrega, $id)) {
            die('El vehículo no está disponible en esas fechas');
        }

        $data = [
            'id_cliente' => $id_cliente,
            'id_vehiculo' => $id_vehiculo,
            'fecha_recogida' => $fecha_recogida,
            'fecha_entrega' => $fecha_entrega,
            'precio_total' => $precio_total,
            'estado' => $_POST['estado']
        ];
        Reserva::update($id, $data);

        header('Location: /Sistema_RentACar/index.php?controller=Reservas&action=index&success=editada');
        exit;
    }

    public function cancelar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $db = Database::connect();
            $db->prepare("UPDATE tbReservas SET estado = 'cancelada' WHERE id_reserva = ?")->execute([$id]);
        }
        header('Location: /Sistema_RentACar/index.php?controller=Reservas&action=index&success=cancelada');
        exit;
    }
}