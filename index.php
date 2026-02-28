<?php
// index.php - Router principal
session_start();

// Constantes de rutas (ajusta BASE_URL según tu entorno)
define('PROJECT_ROOT_FS', __DIR__);
define('BASE_URL', '/Sistema_RentACar'); // Cambia si tu proyecto está en otra ruta

if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? null;
$action     = $_GET['action'] ?? 'index';
$vista      = $_GET['v'] ?? null;

// Mapeo de controladores del panel de administración
$adminControllers = [
    'Auth'          => 'AuthController',
    'GoogleAuth'    => 'GoogleAuthController',
    'Dashboard'     => 'DashboardController',
    'Clientes'      => 'ClientesController',
    'Vehiculos'     => 'VehiculosController',
    'Devolucion'    => 'DevolucionController',
    'Checklist'     => 'ChecklistController',
    'Usuarios'      => 'UsuariosController',
    'Reportes'      => 'ReportesController',
    'Contratos'     => 'ContratosController',
    'Reservas'      => 'ReservasController',
    'Multas'        => 'MultasController',
    'Mantenimientos'=> 'MantenimientosController',
    'Perfil'        => 'PerfilController',
    'AsistenciaAdmin'=> 'AsistenciaAdminController',
    
];
// ============================================
// SI NO HAY CONTROLADOR NI VISTA, REDIRIGIR AL LOGIN
// ============================================
if (!$controller && !$vista) {
    // Si ya está logueado, redirigir al dashboard
    if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
        header('Location: ' . url('index.php?controller=Dashboard&action=index'));
        exit;
    } else {
        header('Location: ' . url('index.php?controller=Auth&action=login'));
        exit;
    }
}

// ============================================
// RUTAS CON CONTROLADOR (nuevo sistema)
// ============================================
if (isset($adminControllers[$controller])) {
    $archivoControlador = __DIR__ . "/controller/Admin/{$adminControllers[$controller]}.php";
    if (file_exists($archivoControlador)) {
        require_once $archivoControlador;
        $obj = new $adminControllers[$controller]();
        if (method_exists($obj, $action)) {
            $obj->$action();
        } else {
            die("Error 404: Acción '{$action}' no encontrada en el controlador '{$controller}'.");
        }
    } else {
        die("Error 404: Controlador '{$controller}' no encontrado.");
    }
}
// ============================================
// RUTAS POR VISTA DIRECTA (sistema antiguo, compatible)
// ============================================
elseif ($vista) {
    // Lista de vistas que requieren autenticación
    $vistasProtegidas = ['clientes', 'devolucion', 'disponibilidad', 'checklist'];

    if (in_array($vista, $vistasProtegidas)) {
        if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
            header('Location: ' . url('index.php?controller=Auth&action=login'));
            exit;
        }
    }

    $archivoVista = __DIR__ . "/views/AdministracionClientesOperaciones/{$vista}.php";
    if (file_exists($archivoVista)) {
        require $archivoVista;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Vista no encontrada.";
    }
}
// ============================================
// Si no hay controller ni vista (ya manejado arriba)
// ============================================
else {
    header('Location: ' . url('index.php?controller=Auth&action=login'));
    exit;
}