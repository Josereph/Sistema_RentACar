<?php
// helpers/ClienteEmailHelper.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ClienteEmailHelper
{
    private $mail;
    private $config;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        // Configuración SMTP (ajusta según tu proveedor)
        $this->config = [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'smtp_secure' => 'tls',
            'username' => 'josephorell05@gmail.com', // Tu correo
            'password' => 'aqbfkmvfgmeznhyd',       // Contraseña de aplicación
            'from_email' => 'josephorell05@gmail.com',
            'from_name' => 'GoCar Rent A Car'
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

    /**
     * Envía correo de confirmación de reserva al cliente
     * @param int $reserva_id
     * @return bool
     */
    public static function enviarConfirmacion($reserva_id)
    {
        // Obtener datos de la reserva
        $db = Database::connect();
        $sql = "SELECT r.*, 
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.correo as cliente_correo,
                       v.marca, v.modelo, v.numero_placa
                FROM tbReservas r
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE r.id_reserva = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$reserva_id]);
        $reserva = $stmt->fetch();
        if (!$reserva) {
    error_log("No se encontró la reserva ID $reserva_id");
    return false;
}
if (empty($reserva['cliente_correo'])) {
    error_log("El cliente no tiene correo electrónico para la reserva $reserva_id");
    return false;
}

        if (!$reserva) {
            error_log("ClienteEmailHelper: No se encontró la reserva ID $reserva_id");
            return false;
        }

        $helper = new self();
        return $helper->enviarCorreo($reserva);
    }

    private function enviarCorreo($reserva)
    {
        try {
            $this->setupServer();
            $this->mail->addAddress($reserva['cliente_correo'], $reserva['cliente_nombre'] . ' ' . $reserva['cliente_apellido']);
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = 'Confirmación de reserva #' . $reserva['id_reserva'] . ' - GoCar';

            // Cuerpo del mensaje
            $body = $this->generarCuerpoHTML($reserva);
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            $this->mail->send();
            error_log("ClienteEmailHelper: Correo enviado a " . $reserva['cliente_correo']);
            return true;
        } catch (Exception $e) {
            error_log("ClienteEmailHelper: Error al enviar correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function generarCuerpoHTML($reserva)
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