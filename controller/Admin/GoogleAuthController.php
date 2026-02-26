<?php
// controller/Admin/GoogleAuthController.php
require_once __DIR__ . '/../../config/google.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/Admin/Usuario.php';

class GoogleAuthController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $client = new Google_Client();
        // 🔓 Deshabilitar verificación SSL para entorno local
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

            $usuario = Usuario::findByOAuth('google', $userInfo->id);

            if (!$usuario) {
                $data = [
                    'nombre' => $userInfo->name,
                    'correo' => $userInfo->email,
                    'imagen' => $userInfo->picture,
                    'provider' => 'google',
                    'uid' => $userInfo->id
                ];
                $id_usuario = Usuario::createFromGoogle($data);
                $usuario = Usuario::findById($id_usuario);
            }

            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $usuario['id_usuario'];
            $_SESSION['admin_nombre'] = $usuario['nombre'];
            $_SESSION['admin_rol'] = $usuario['rol_nombre'];

            header('Location: /Sistema_RentACar/index.php?controller=Dashboard&action=index');
            exit;

        } catch (Exception $e) {
            die('Error en autenticación con Google: ' . $e->getMessage());
        }
    }
}