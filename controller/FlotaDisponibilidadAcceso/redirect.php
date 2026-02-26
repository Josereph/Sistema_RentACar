<?php

use Google\Service\Oauth2;

session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/FlotaDisponibilidadAcceso/db.php';

$client = new Google\Client();
$client->setClientId("");
$client->setClientSecret("");
$client->setRedirectUri("http://localhost/Sistema_RentACar/controller/FlotaDisponibilidadAcceso/redirect.php");

$httpClient = new GuzzleHttp\Client([
    'verify' => false
]);

$client->setHttpClient($httpClient);
if (!isset($_GET['code'])) {
    exit("No se recibió código de autorización.");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (isset($token['error'])) {
    exit("Error al obtener el token.");
}

$client->setAccessToken($token);

$oauth = new Oauth2($client);
$user = $oauth->userinfo->get();

$nombre = $user->name;
$correo = $user->email;
$conexion = new mysqli("localhost", "root", "roothq", "rent_a_car");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$stmt = $conexion->prepare("SELECT id_usuario, nombre, id_rol FROM tbUsuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['id_rol'] = $usuario['id_rol'];

} else {
    $id_rol = 2; 
    $estado = 'activo';

    $stmt = $conexion->prepare("
        INSERT INTO tbUsuarios 
        (id_rol, nombre, correo, password_hash, estado) 
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isss", $id_rol, $nombre, $correo, $estado);
    $stmt->execute();

    $_SESSION['id_usuario'] = $stmt->insert_id;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['id_rol'] = $id_rol;
}

if ($_SESSION['id_rol'] == 1) {
    header("Location: ../../views/FlotaDisponibilidadAcceso/catalogoprueba.php");
} else {
    header("Location: ../../views/FlotaDisponibilidadAcceso/catalogoprueba.php");
}

exit();
?>