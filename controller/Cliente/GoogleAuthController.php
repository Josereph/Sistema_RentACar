<?php
// controller/Cliente/GoogleAuthController.php
require_once __DIR__ . '/../../config/google.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ClienteModel.php';

class GoogleAuthController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = new Google_Client();
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope("email");
        $client->addScope("profile");

        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit;
    }

    public function callback()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = new Google_Client();
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);

        if (!isset($_GET['code'])) {
            die('Error: No se recibió el código de autorización');
        }

        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['error'])) {
                die('Error al obtener token: ' . $token['error']);
            }
            $client->setAccessToken($token);

            $oauth = new Google_Service_Oauth2($client);
            $userInfo = $oauth->userinfo->get();

            $partes = explode(" ", trim($userInfo->name));
            $nombre = $partes[0];
            $apellido = isset($partes[1]) ? implode(" ", array_slice($partes, 1)) : "";

            $clienteModel = new ClienteModel();
            $cliente = $clienteModel->obtenerPorCorreo($userInfo->email);

            if (!$cliente) {
                $id_cliente = $clienteModel->crearCliente($nombre, $apellido, $userInfo->email);
            } else {
                $id_cliente = $cliente['id_cliente'];
            }

            session_regenerate_id(true);
            $_SESSION['cliente_logged'] = true;
            $_SESSION['cliente_id']     = $id_cliente;
            $_SESSION['cliente_nombre'] = $nombre . ' ' . $apellido;
            $_SESSION['cliente_email']  = $userInfo->email;
            $_SESSION['cliente_oauth'] = [
                'provider' => 'google',
                'uid'      => $userInfo->id,
                'imagen'   => $userInfo->picture,
            ];

            session_write_close();
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Catalogo&action=index');
            exit;

        } catch (Exception $e) {
            die('Error en autenticación con Google: ' . $e->getMessage());
        }
    }
}