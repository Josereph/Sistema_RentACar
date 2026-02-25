<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';

class UsuariosController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $this->checkRol(['superadmin']); // Solo superadmin puede ver usuarios

        $db = Database::connect();
        $usuarios = $db->query("
            SELECT u.*, r.nombre as rol_nombre
            FROM tbUsuarios u
            INNER JOIN tbRoles r ON u.id_rol = r.id_rol
            ORDER BY u.id_usuario DESC
        ")->fetchAll();

        $roles = $db->query("SELECT * FROM tbRoles")->fetchAll();

        $titulo = 'Gestión de Usuarios';
        $seccion = 'usuarios';
        include __DIR__ . '/../../views/admin/usuarios/index.php';
    }

    public function crear()
    {
        parent::__construct();
        $this->checkRol(['superadmin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $id_rol = $_POST['id_rol'];
            $estado = $_POST['estado'] ?? 'activo';

            $db = Database::connect();
            $stmt = $db->prepare("INSERT INTO tbUsuarios (id_rol, nombre, correo, password_hash, estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_rol, $nombre, $correo, $password, $estado]);
            header('Location: index.php?controller=Usuarios&action=index');
        }
    }

    public function editar()
    {
        parent::__construct();
        $this->checkRol(['superadmin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $id_rol = $_POST['id_rol'];
            $estado = $_POST['estado'];

            $db = Database::connect();
            $sql = "UPDATE tbUsuarios SET nombre = ?, correo = ?, id_rol = ?, estado = ? WHERE id_usuario = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$nombre, $correo, $id_rol, $estado, $id]);

            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $db->prepare("UPDATE tbUsuarios SET password_hash = ? WHERE id_usuario = ?")->execute([$password, $id]);
            }
            header('Location: index.php?controller=Usuarios&action=index');
        }
    }

    public function eliminar()
    {
        parent::__construct();
        $this->checkRol(['superadmin']);
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $db = Database::connect();
            $db->prepare("DELETE FROM tbUsuarios WHERE id_usuario = ?")->execute([$id]);
        }
        header('Location: index.php?controller=Usuarios&action=index');
    }

    public function getJson()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbUsuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        header('Content-Type: application/json');
        echo json_encode($usuario);
        exit;
    }
}