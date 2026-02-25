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
}