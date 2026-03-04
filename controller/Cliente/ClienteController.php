<?php
// controller/Cliente/ClienteController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ClienteModel.php';

class ClienteController {
    private $model;

    public function __construct() {
        $this->model = new ClienteModel();
    }

    /**
     * Mostrar formulario de registro
     */
    public function registro() {
        require_once __DIR__ . '/../../views/admin/auth/registro.php';
    }

    /**
     * Procesar el registro manual
     */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=registro');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validaciones básicas
        if (empty($nombre) || empty($apellido) || empty($correo) || empty($password)) {
            die('Todos los campos son obligatorios');
        }

        if ($password !== $confirm_password) {
            die('Las contraseñas no coinciden');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            die('Correo electrónico no válido');
        }

        // Verificar si el correo ya existe
        $existente = $this->model->obtenerPorCorreo($correo);
        if ($existente) {
            die('El correo ya está registrado');
        }

        // Crear cliente
        $id_cliente = $this->model->crearCliente($nombre, $apellido, $correo, $password);

        if ($id_cliente) {
            // Iniciar sesión automáticamente
            $_SESSION['cliente_id'] = $id_cliente;
            $_SESSION['cliente_logged'] = true;
            $_SESSION['cliente_nombre'] = $nombre . ' ' . $apellido;

            // Redirigir a completar perfil (si es necesario)
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=perfil');
            exit;
        } else {
            die('Error al crear el cliente');
        }
    }

    /**
     * Mostrar perfil del cliente
     */
    public function perfil() {
        if (!isset($_SESSION['cliente_id'])) {
            header('Location: /Sistema_RentACar/views/admin/auth/login.php');
            exit;
        }

        $id_cliente = $_SESSION['cliente_id'];
        $cliente = $this->model->obtenerPorId($id_cliente);

        if (!$cliente) {
            session_destroy();
            header('Location: /Sistema_RentACar/views/admin/auth/login.php');
            exit;
        }

        $perfilIncompleto = !$this->model->perfilCompleto($id_cliente);

        require_once __DIR__ . '/../../views/ReservaCatalogo/views/perfil.php';
    }

    /**
     * Actualizar datos del perfil (DUI, teléfono, dirección)
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=perfil');
            exit;
        }

        if (!isset($_SESSION['cliente_id'])) {
            header('Location: /Sistema_RentACar/views/admin/auth/login.php');
            exit;
        }

        $id_cliente = $_SESSION['cliente_id'];

        // Validar formato DUI (00000000-0)
        $dui = $_POST['DUI'] ?? '';
        if (!preg_match('/^[0-9]{8}-[0-9]{1}$/', $dui)) {
            die('DUI inválido. Formato correcto: 12345678-9');
        }

        // Validar teléfono (0000-0000)
        $telefono = $_POST['telefono'] ?? '';
        if (!preg_match('/^[0-9]{4}-[0-9]{4}$/', $telefono)) {
            die('Teléfono inválido. Formato correcto: 7123-4567');
        }

        $direccion = trim($_POST['direccion'] ?? '');
        if (empty($direccion)) {
            die('La dirección es obligatoria.');
        }

        $actualizado = $this->model->actualizarDatos($id_cliente, $dui, $telefono, $direccion);

        if ($actualizado) {
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=perfil&success=1');
        } else {
            header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=perfil&error=1');
        }
        exit;
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_start();
        unset($_SESSION['cliente_id'], $_SESSION['cliente_logged'], $_SESSION['cliente_nombre']);
        session_destroy();
        header('Location: /Sistema_RentACar/');
        exit;
    }
}