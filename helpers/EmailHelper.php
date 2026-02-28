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
        'password' => 'aqbfkmvfgmeznhyd', // sin espacios
        'from_email' => 'josephorell05@gmail.com',
        'from_name' => 'GoCar Rent A Car'
    ];

    /**
     * Envía un correo usando PHPMailer con la plantilla base.
     */
    public static function send($to, $subject, $body, $altBody = '')
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambiar a DEBUG_SERVER para depuración
            $mail->isSMTP();
            $mail->Host       = self::$config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$config['username'];
            $mail->Password   = self::$config['password'];
            $mail->SMTPSecure = self::$config['encryption'];
            $mail->Port       = self::$config['port'];

            // Deshabilitar verificación SSL para entorno local (eliminar en producción)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom(self::$config['from_email'], self::$config['from_name']);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody ?: strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Plantilla base con header, footer y estilos.
     */
    private static function getBaseTemplate($content)
    {
        $logoUrl = '/Sistema_RentACar/assets/img/img.jpeg'; // Ajusta si es necesario
        $baseUrl = 'http://localhost/Sistema_RentACar';    // Para enlaces (cambiar en producción)

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCar Rent A Car</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f6f7f8;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #101922;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #137fec;
        }
        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .header h1 span {
            color: #137fec;
            font-weight: 700;
        }
        .content {
            padding: 30px 25px;
            color: #1e293b;
            line-height: 1.6;
        }
        .content h2 {
            color: #137fec;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .details-box {
            background-color: #f8fafc;
            border-left: 4px solid #137fec;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .details-box strong {
            color: #101922;
        }
        .footer {
            background-color: #f6f7f8;
            padding: 20px 25px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #137fec;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .button {
            display: inline-block;
            background-color: #137fec;
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #0e64b9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{$logoUrl}" alt="GoCar Logo">
            <h1>GoCar <span>Rent A Car</span></h1>
        </div>
        <div class="content">
            {$content}
        </div>
        <div class="footer">
            <p>© 2025 GoCar Rent A Car. Todos los derechos reservados.</p>
            <p>
                <a href="{$baseUrl}">Visita nuestro sitio</a> |
                <a href="mailto:soporte@gocar.com">soporte@gocar.com</a>
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    // ==================== MÉTODOS ESPECÍFICOS ====================

    /**
     * Correo de confirmación de reserva.
     */
    public static function sendReservaConfirmada($to, $nombreCliente, $datosReserva)
    {
        $subject = "✅ Reserva confirmada en GoCar";
        $content = "
            <h2>¡Hola, {$nombreCliente}!</h2>
            <p>Tu reserva ha sido confirmada exitosamente. Estamos listos para que disfrutes de tu viaje.</p>
            <div class='details-box'>
                <p><strong>Vehículo:</strong> {$datosReserva['vehiculo']}</p>
                <p><strong>Fecha de recogida:</strong> {$datosReserva['fecha_recogida']}</p>
                <p><strong>Fecha de entrega:</strong> {$datosReserva['fecha_entrega']}</p>
                <p><strong>Total:</strong> \${$datosReserva['total']}</p>
            </div>
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p style='text-align: center;'>
                <a href='http://localhost/Sistema_RentACar/index.php?controller=Reservas&action=index' class='button'>Ver mis reservas</a>
            </p>
            <p>Gracias por confiar en GoCar. ¡Buen viaje!</p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }

    /**
     * Recordatorio de devolución (un día antes).
     */
    public static function sendRecordatorioDevolucion($to, $nombreCliente, $datos)
    {
        $subject = "⏰ Recordatorio: Devolución de tu vehículo mañana";
        $content = "
            <h2>Hola, {$nombreCliente}</h2>
            <p>Te recordamos que la devolución del vehículo <strong>{$datos['vehiculo']}</strong> está programada para mañana ({$datos['fecha_entrega']}).</p>
            <div class='details-box'>
                <p><strong>Vehículo:</strong> {$datos['vehiculo']}</p>
                <p><strong>Fecha de entrega:</strong> {$datos['fecha_entrega']}</p>
            </div>
            <p>Por favor, asegúrate de devolverlo a tiempo para evitar cargos adicionales. Si tienes alguna duda, contáctanos.</p>
            <p style='text-align: center;'>
                <a href='http://localhost/Sistema_RentACar/index.php?controller=Devolucion&action=listado' class='button'>Ver detalles</a>
            </p>
            <p>Gracias por elegir GoCar.</p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }

    /**
     * Notificación de multa aplicada.
     */
    public static function sendMultaAplicada($to, $nombreCliente, $datosMulta)
    {
        $subject = "⚠️ Se ha aplicado una multa a tu reserva";
        $content = "
            <h2>Hola, {$nombreCliente}</h2>
            <p>Se ha aplicado una multa a tu reserva por el siguiente concepto:</p>
            <div class='details-box'>
                <p><strong>Motivo:</strong> {$datosMulta['motivo']}</p>
                <p><strong>Monto:</strong> \${$datosMulta['monto']}</p>
            </div>
            <p>El cargo será procesado según el método de pago registrado. Si consideras que esto es un error, por favor contáctanos a la brevedad.</p>
            <p style='text-align: center;'>
                <a href='http://localhost/Sistema_RentACar/index.php?controller=Multas&action=index' class='button'>Ver mis multas</a>
            </p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }

    /**
     * Confirmación de devolución completada.
     */
    public static function sendDevolucionCompletada($to, $nombreCliente, $datos)
    {
        $subject = "✅ Devolución completada con éxito";
        $content = "
            <h2>Hola, {$nombreCliente}</h2>
            <p>La devolución del vehículo <strong>{$datos['vehiculo']}</strong> se ha registrado correctamente.</p>
            <p>Gracias por preferirnos. Esperamos que hayas tenido una excelente experiencia con GoCar.</p>
            <p>Si tienes alguna pregunta sobre tu factura o el estado de tu cuenta, no dudes en escribirnos.</p>
            <p style='text-align: center;'>
                <a href='http://localhost/Sistema_RentACar/index.php?controller=Devolucion&action=listado' class='button'>Ver historial</a>
            </p>
            <p>¡Te esperamos pronto!</p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }

    /**
     * Notificación de checklist completado.
     */
    public static function sendChecklistCompletado($to, $nombreCliente, $datos)
    {
        $subject = "📋 Inspección completada";
        $content = "
            <h2>Hola, {$nombreCliente}</h2>
            <p>La inspección del vehículo <strong>{$datos['vehiculo']}</strong> ha sido completada.</p>
            <p>Si hubo algún hallazgo durante la inspección, nuestro equipo se comunicará contigo. De lo contrario, puedes estar tranquilo, todo está en orden.</p>
            <p>Gracias por confiar en GoCar.</p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }

    /**
     * Correo de bienvenida (para cuando un cliente se registra).
     */
    public static function sendBienvenida($to, $nombreCliente)
    {
        $subject = "🎉 ¡Bienvenido a GoCar!";
        $content = "
            <h2>¡Hola, {$nombreCliente}!</h2>
            <p>Nos alegra darte la bienvenida a GoCar Rent A Car. Ahora formas parte de nuestra comunidad de viajeros.</p>
            <p>Con GoCar puedes:</p>
            <ul>
                <li>Explorar nuestra flota de vehículos de alta calidad.</li>
                <li>Realizar reservas en línea de forma rápida y segura.</li>
                <li>Recibir notificaciones y recordatorios personalizados.</li>
            </ul>
            <p style='text-align: center;'>
                <a href='http://localhost/Sistema_RentACar/index.php?controller=Vehiculos&action=index' class='button'>Ver vehículos</a>
            </p>
            <p>Si tienes alguna duda, estamos aquí para ayudarte.</p>
            <p>¡Que comience la aventura!</p>
        ";
        $body = self::getBaseTemplate($content);
        return self::send($to, $subject, $body);
    }
}