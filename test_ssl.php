<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'josephorell05@gmail.com';
    $mail->Password   = 'aqbfkmvfgmeznhyd'; // sin espacios
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Opciones SSL para evitar error de certificado (solo desarrollo)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->setFrom('josephorell05@gmail.com', 'Prueba');
    $mail->addAddress('josephorell05@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Prueba PHPMailer';
    $mail->Body    = '<h1>Funciona con SSL desactivado</h1>';

    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}