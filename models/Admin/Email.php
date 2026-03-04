<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private $mail;
    private $config;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        // Configuración del servidor SMTP (ajusta según tu proveedor)
        $this->config = [
            'host' => 'smtp.gmail.com', // Ejemplo con Gmail
            'port' => 587,
            'smtp_secure' => 'tls',
            'username' => 'josephorell05@gmail.com', // Cambia por tu correo
            'password' => 'aqbf kmvf gmez nhyd',       // Cambia por tu contraseña o token
            'from_email' => 'josephorell05@gmail.com',
            'from_name' => 'Sistema Rent A Car'
        ];
    }

    private function setupServer()
    {
        $this->mail->isSMTP();
        $this->mail->Host       = $this->config['host'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->config['username'];
        $this->mail->Password   = $this->config['password'];
        $this->mail->SMTPSecure = $this->config['smtp_secure'];
        $this->mail->Port       = $this->config['port'];
        $this->mail->setFrom($this->config['from_email'], $this->config['from_name']);
    }

    public function enviar($destinatario, $nombre, $asunto, $cuerpoHTML, $cuerpoTexto = '')
    {
        try {
            $this->setupServer();
            $this->mail->addAddress($destinatario, $nombre);
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body    = $cuerpoHTML;
            $this->mail->AltBody = $cuerpoTexto ?: strip_tags($cuerpoHTML);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Loggear error
            error_log("Error al enviar correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    // Método para guardar en la tabla tbNotificacionesEmail
    public static function guardarNotificacion($id_reserva, $destinatario, $asunto, $mensaje, $estado = 'pendiente')
    {
        $db = Database::connect();
        $sql = "INSERT INTO tbNotificacionesEmail (id_reserva, destinatario, asunto, mensaje_resumen, estado, fecha_envio)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $fecha = $estado === 'enviado' ? date('Y-m-d H:i:s') : null;
        return $stmt->execute([$id_reserva, $destinatario, $asunto, $mensaje, $estado, $fecha]);
    }

    public static function enviarConfirmacionReserva($reserva_id)
{
    // Obtener datos de la reserva, cliente y vehículo
    require_once __DIR__ . '/../../config/db.php'; // Asegurar conexión
    $db = Database::connect();
    $sql = "SELECT r.*, 
                   c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.correo as cliente_correo,
                   v.marca, v.modelo, v.numero_placa, v.precio_dia
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE r.id_reserva = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$reserva_id]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        return false;
    }

    $asunto = 'Confirmación de reserva #' . $reserva_id . ' - GoCar';
    $cuerpoHTML = self::generarCuerpoConfirmacion($reserva);
    $cuerpoTexto = strip_tags($cuerpoHTML);

    $email = new self();
    $enviado = $email->enviar(
        $reserva['cliente_correo'],
        $reserva['cliente_nombre'] . ' ' . $reserva['cliente_apellido'],
        $asunto,
        $cuerpoHTML,
        $cuerpoTexto
    );

    // Guardar notificación
    $estado = $enviado ? 'enviado' : 'fallido';
    self::guardarNotificacion(
        $reserva_id,
        $reserva['cliente_correo'],
        $asunto,
        'Reserva confirmada',
        $estado
    );

    return $enviado;
}

private static function generarCuerpoConfirmacion($reserva)
{
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Confirmación de reserva</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
            .header { background-color: #137fec; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { padding: 20px; }
            .details { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>¡Reserva confirmada!</h2>
            </div>
            <div class="content">
                <p>Hola <strong>' . htmlspecialchars($reserva['cliente_nombre'] . ' ' . $reserva['cliente_apellido']) . '</strong>,</p>
                <p>Tu reserva ha sido confirmada exitosamente. Aquí están los detalles:</p>
                
                <div class="details">
                    <table>
                        <tr><td><strong>N° de reserva:</strong></td><td>' . $reserva['id_reserva'] . '</td></tr>
                        <tr><td><strong>Vehículo:</strong></td><td>' . htmlspecialchars($reserva['marca'] . ' ' . $reserva['modelo']) . '</td></tr>
                        <tr><td><strong>Placa:</strong></td><td>' . htmlspecialchars($reserva['numero_placa']) . '</td></tr>
                        <tr><td><strong>Fecha de recogida:</strong></td><td>' . date('d/m/Y', strtotime($reserva['fecha_recogida'])) . '</td></tr>
                        <tr><td><strong>Fecha de entrega:</strong></td><td>' . date('d/m/Y', strtotime($reserva['fecha_entrega'])) . '</td></tr>
                        <tr><td><strong>Total pagado:</strong></td><td>$' . number_format($reserva['precio_total'], 2) . '</td></tr>
                    </table>
                </div>
                
                <p>Puedes ver los detalles de tu reserva en tu perfil.</p>
                <p>¡Gracias por confiar en GoCar!</p>
            </div>
            <div class="footer">
                <p>© ' . date('Y') . ' GoCar Rent A Car. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>';
    return $html;
}


}