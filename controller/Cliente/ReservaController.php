<?php
// controller/Cliente/ReservaController.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Pago.php';
require_once __DIR__ . '/../../helpers/EmailHelper.php';

class ReservaController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['cliente_logged'])) {
            header('Location: ' . url('index.php?area=cliente&controller=Auth&action=login'));
            exit;
        }
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('index.php?area=cliente&controller=Catalogo&action=index'));
            exit;
        }

        $id_vehiculo = $_POST['id_vehiculo'];
        $fecha_recogida = $_POST['fecha_recogida'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $metodo_pago = $_POST['metodo_pago'] ?? null;

        if (!Reserva::disponible($id_vehiculo, $fecha_recogida, $fecha_entrega)) {
            $_SESSION['error'] = 'Vehículo no disponible en esas fechas.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $vehiculo = Vehiculo::find($id_vehiculo);
        $dias = (strtotime($fecha_entrega) - strtotime($fecha_recogida)) / 86400;
        $precio_total = $dias * $vehiculo['precio_dia'];

        $db = Database::connect();
        $db->beginTransaction();

        try {
            $id_reserva = Reserva::create([
                'id_cliente' => $_SESSION['cliente_data']['id_cliente'],
                'id_vehiculo' => $id_vehiculo,
                'fecha_recogida' => $fecha_recogida,
                'fecha_entrega' => $fecha_entrega,
                'precio_total' => $precio_total,
                'estado' => $metodo_pago ? 'confirmada' : 'pendiente'
            ]);

            if ($metodo_pago) {
                Pago::create($id_reserva, $precio_total, $metodo_pago, [
                    ['concepto' => 'renta', 'monto' => $precio_total]
                ]);
            }

            $db->commit();

            // Enviar correo
            EmailHelper::sendReservaConfirmada(
                $_SESSION['cliente_data']['correo'],
                $_SESSION['cliente_data']['nombre'],
                [
                    'vehiculo' => $vehiculo['marca'] . ' ' . $vehiculo['modelo'],
                    'fecha_recogida' => date('d/m/Y', strtotime($fecha_recogida)),
                    'fecha_entrega' => date('d/m/Y', strtotime($fecha_entrega)),
                    'total' => number_format($precio_total, 2)
                ]
            );

            $_SESSION['success'] = 'Reserva creada exitosamente.';
            header('Location: ' . url('index.php?area=cliente&controller=Reserva&action=misReservas'));
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }

    public function misReservas()
    {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT r.*, v.marca, v.modelo, v.numero_placa
            FROM tbReservas r
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE r.id_cliente = ?
            ORDER BY r.fecha_reserva DESC
        ");
        $stmt->execute([$_SESSION['cliente_data']['id_cliente']]);
        $reservas = $stmt->fetchAll();
        require PROJECT_ROOT_FS . '/views/cliente/reservas/mis_reservas.php';
    }

    public function cancelar()
    {
        $id = $_GET['id'] ?? 0;
        $db = Database::connect();
        $db->prepare("UPDATE tbReservas SET estado = 'cancelada' WHERE id_reserva = ? AND id_cliente = ? AND estado IN ('pendiente','confirmada')")
           ->execute([$id, $_SESSION['cliente_data']['id_cliente']]);
        $_SESSION['success'] = 'Reserva cancelada.';
        header('Location: ' . url('index.php?area=cliente&controller=Reserva&action=misReservas'));
        exit;
    }
}