<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/Admin/Email.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Reserva.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Cliente.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Vehiculo.php';

class NotificacionesController extends BaseAdminController
{
    public function recordatorios()
    {
        // Este método puede ser llamado por un cron
        $db = Database::connect();
        // Reservas que vencen en 1 día
        $reservas = $db->query("
            SELECT r.*, c.nombre, c.apellido, c.correo, v.marca, v.modelo
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE r.estado = 'en_curso' 
              AND DATE(r.fecha_entrega) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        ")->fetchAll();

        $email = new Email();
        foreach ($reservas as $r) {
            $asunto = "Recordatorio: Devolución mañana";
            $mensaje = "
                <h1>Recordatorio de devolución</h1>
                <p>Hola {$r['nombre']}, recuerda que mañana debes devolver el vehículo.</p>
                <p><strong>Vehículo:</strong> {$r['marca']} {$r['modelo']}</p>
                <p><strong>Fecha de devolución:</strong> {$r['fecha_entrega']}</p>
            ";
            if ($email->enviar($r['correo'], $r['nombre'], $asunto, $mensaje)) {
                Email::guardarNotificacion($r['id_reserva'], $r['correo'], $asunto, 'Recordatorio enviado', 'enviado');
            }
        }
        echo "Recordatorios enviados.";
    }
}