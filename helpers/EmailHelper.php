<?php
// helpers/EmailHelper.php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailHelper
{
    private static $config = [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'josephorell05@gmail.com',
        'password' => 'aqbfkmvfgmeznhyd', // SIN ESPACIOS
        'from_email' => 'josephorell05@gmail.com',
        'from_name' => 'CarRent Sistema'
    ];

    public static function send($to, $subject, $body, $altBody = '')
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambiar a DEBUG_SERVER para depuración
            $mail->isSMTP();
            $mail->Host       = self::$config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$config['username'];
            $mail->Password   = self::$config['password'];
            $mail->SMTPSecure = self::$config['encryption'];
            $mail->Port       = self::$config['port'];

            // Opciones SSL para evitar errores de certificado en desarrollo
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Remitente y destinatarios
            $mail->setFrom(self::$config['from_email'], self::$config['from_name']);
            $mail->addAddress($to);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Loggear error
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }

    // Métodos específicos para cada tipo de notificación
    public static function sendReservaConfirmada($to, $nombreCliente, $datosReserva)
    {
        $subject = "Reserva confirmada - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>Tu reserva ha sido confirmada exitosamente.</p>
            <p><strong>Detalles de la reserva:</strong></p>
            <ul>
                <li>Vehículo: {$datosReserva['vehiculo']}</li>
                <li>Fecha de recogida: {$datosReserva['fecha_recogida']}</li>
                <li>Fecha de entrega: {$datosReserva['fecha_entrega']}</li>
                <li>Total: \${$datosReserva['total']}</li>
            </ul>
            <p>Gracias por confiar en nosotros.</p>
        ";
        return self::send($to, $subject, $body);
    }

    public static function sendRecordatorioDevolucion($to, $nombreCliente, $datos)
    {
        $subject = "Recordatorio: Devolución de vehículo mañana - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>Te recordamos que la devolución del vehículo <strong>{$datos['vehiculo']}</strong> está programada para mañana ({$datos['fecha_entrega']}).</p>
            <p>Por favor, asegúrate de devolverlo a tiempo para evitar cargos adicionales.</p>
            <p>Si tienes alguna duda, contáctanos.</p>
        ";
        return self::send($to, $subject, $body);
    }

    public static function sendMultaAplicada($to, $nombreCliente, $datosMulta)
    {
        $subject = "Multa aplicada - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>Se ha aplicado una multa a tu reserva por el siguiente concepto:</p>
            <p><strong>Motivo:</strong> {$datosMulta['motivo']}</p>
            <p><strong>Monto:</strong> \${$datosMulta['monto']}</p>
            <p>El cargo será procesado según el método de pago registrado.</p>
        ";
        return self::send($to, $subject, $body);
    }

    public static function sendDevolucionCompletada($to, $nombreCliente, $datos)
    {
        $subject = "Devolución completada - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>La devolución del vehículo <strong>{$datos['vehiculo']}</strong> se ha registrado correctamente.</p>
            <p>Gracias por preferirnos. Esperamos verte pronto.</p>
        ";
        return self::send($to, $subject, $body);
    }

    public static function sendChecklistCompletado($to, $nombreCliente, $datos)
    {
        $subject = "Inspección completada - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>La inspección del vehículo <strong>{$datos['vehiculo']}</strong> ha sido completada.</p>
            <p>Si hubo algún hallazgo, nuestro equipo se comunicará contigo.</p>
        ";
        return self::send($to, $subject, $body);
    }

    public static function sendMantenimientoProgramado($to, $nombreCliente, $datos)
    {
        $subject = "Mantenimiento programado - CarRent";
        $body = "
            <h2>Hola $nombreCliente</h2>
            <p>El vehículo <strong>{$datos['vehiculo']}</strong> ha sido programado para mantenimiento.</p>
            <p>Fecha de inicio: {$datos['fecha_inicio']}</p>
            <p>Descripción: {$datos['descripcion']}</p>
        ";
        return self::send($to, $subject, $body);
    }
}