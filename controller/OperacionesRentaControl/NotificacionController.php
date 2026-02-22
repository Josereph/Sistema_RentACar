<?php

require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../config/mail_config.php';

class NotificacionController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function enviarRecordatoriosDevolucion() {

        $notificacionModel = new Notificacion($this->db);
        $reservas = $notificacionModel->obtenerReservasProximas();

        foreach ($reservas as $reserva) {

            $nombreCompleto = $reserva['nombre'] . " " . $reserva['apellido'];
            $correo = $reserva['correo'];
            $id_reserva = $reserva['id_reserva'];

            $asunto = "Recordatorio de devolución de vehículo";

            $mensaje = "
                Hola $nombreCompleto,<br><br>
                Le recordamos que su vehículo debe ser devuelto el día <b>{$reserva['fecha_entrega']}</b>.<br><br>
                Evite cargos adicionales por retraso.<br><br>
                Gracias por confiar en nuestro servicio.
            ";

            $enviado = enviarCorreo($correo, $asunto, $mensaje);

            if ($enviado) {
                $notificacionModel->registrarNotificacion(
                    $id_reserva,
                    $correo,
                    $asunto,
                    "Recordatorio de devolución enviado.",
                    "enviado"
                );
            } else {
                $notificacionModel->registrarNotificacion(
                    $id_reserva,
                    $correo,
                    $asunto,
                    "Error al enviar recordatorio.",
                    "error",
                    "Fallo en función mail()"
                );
            }
        }

        return count($reservas);
    }
}
