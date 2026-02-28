<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/Admin/Usuario.php';
require_once __DIR__ . '/../../config/db.php';

class PerfilController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $usuario = Usuario::findById($_SESSION['admin_id']);
        if (!$usuario || !is_array($usuario)) {
            die('Error: No se pudo cargar la información del usuario.');
        }
        $titulo = 'Mi Perfil';
        $seccion = 'perfil';
        require PROJECT_ROOT_FS . '/views/admin/perfil/index.php';
    }

    public function actualizar()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $id = $_SESSION['admin_id'];
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        if (empty($nombre) || empty($correo)) {
            $_SESSION['error_perfil'] = "Nombre y correo son obligatorios";
            header('Location: /Sistema_RentACar/index.php?controller=Perfil&action=index');
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_perfil'] = "Correo electrónico no válido";
            header('Location: /Sistema_RentACar/index.php?controller=Perfil&action=index');
            exit;
        }

        $db = Database::connect();
        $stmt = $db->prepare("SELECT id_usuario FROM tbUsuarios WHERE correo = ? AND id_usuario != ?");
        $stmt->execute([$correo, $id]);
        if ($stmt->fetch()) {
            $_SESSION['error_perfil'] = "El correo ya está registrado por otro usuario";
            header('Location: /Sistema_RentACar/index.php?controller=Perfil&action=index');
            exit;
        }

        if (!empty($password)) {
            if ($password !== $confirmar) {
                $_SESSION['error_perfil'] = "Las contraseñas no coinciden";
                header('Location: /Sistema_RentACar/index.php?controller=Perfil&action=index');
                exit;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE tbUsuarios SET nombre = ?, correo = ?, password_hash = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $correo, $hash, $id]);
        } else {
            $stmt = $db->prepare("UPDATE tbUsuarios SET nombre = ?, correo = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $correo, $id]);
        }

        $_SESSION['admin_nombre'] = $nombre;
        $_SESSION['success_perfil'] = "Perfil actualizado correctamente";
        header('Location: /Sistema_RentACar/index.php?controller=Perfil&action=index');
        exit;
    }
}