<?php

use GuzzleHttp\Client;

require_once __DIR__ . '/../../vendor/autoload.php';

$client = new Google\Client();

$client->setClientId("");
$client->setClientSecret("");
$client->setRedirectUri("http://localhost/Sistema_RentACar/controller/FlotaDisponibilidadAcceso/redirect.php");

$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();
?>
<!doctype html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Iniciar Sesión</h3>
                        <form>
                            <div class="form-group mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" placeholder="">
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" placeholder="">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mb-3">Entrar</button>
                        </form>
                        <div class="text-center mb-3">
                            <span class="text-muted">o</span>
                        </div>
                        <a href="<?= $url ?>" class="btn btn-outline-danger btn-block d-flex align-items-center justify-content-center">
                            <i class="fab fa-google mr-2"></i> Sign in with Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>