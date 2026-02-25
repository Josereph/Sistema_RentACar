<?php
// index.php - Router principal
session_start();

// ============================================
// 1. CONSTANTES DE RUTAS (AJUSTA SEGÚN TU ENTORNO)
// ============================================
define('PROJECT_ROOT_FS', __DIR__); // Ruta absoluta en el sistema de archivos

// 🔧 IMPORTANTE: Cambia esto por la URL base de tu proyecto.
// Ejemplo: si accedes vía http://localhost/Sistema_RentACar/ , entonces:
// define('BASE_URL', '/Sistema_RentACar');
// Si usas un virtual host como http://rentacar.local/ , pon solo '' o '/'.
define('BASE_URL', '/Sistema_RentACar'); // <-- AJUSTA ESTO

// Función auxiliar para generar URLs (si no existe)
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// ============================================
// 2. ENRUTAMIENTO
// ============================================
$controller = $_GET['controller'] ?? 'home';
$action     = $_GET['action']     ?? 'index';
$vista      = $_GET['v']           ?? null; // Para el sistema antiguo (por vistas)

// Mapeo de controladores del panel de administración
$adminControllers = [
    'Auth'          => 'AuthController',
    'Dashboard'     => 'DashboardController',
    'Clientes'      => 'ClientesController',
    'Vehiculos'     => 'VehiculosController',
    'Devolucion'    => 'DevolucionController',
    'Checklist'     => 'ChecklistController',
    'Usuarios'      => 'UsuariosController',
    'Reportes'      => 'ReportesController',
    'Contratos'     => 'ContratosController',
    'Reservas' => 'ReservasController',
    'Multas' => 'MultasController',
    'Mantenimientos' => 'MantenimientosController',
    'Notificaciones' => 'NotificacionesController',
    
];

// ============================================
// 3. RUTAS CON CONTROLADOR (nuevo sistema)
// ============================================
if (isset($adminControllers[$controller])) {
    // Construir ruta al archivo del controlador
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
// 4. RUTAS POR VISTA DIRECTA (sistema antiguo, compatible)
// ============================================
elseif ($vista) {
    // Lista de vistas que requieren autenticación
    $vistasProtegidas = ['clientes', 'devolucion', 'disponibilidad', 'checklist'];

    if (in_array($vista, $vistasProtegidas)) {
        // Verificar sesión
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
// 5. PÁGINA DE INICIO POR DEFECTO
// ============================================
else {
    // Si no hay controller ni vista, redirigir al login o mostrar home público
    // Por ahora, redirigimos al login del admin
    header('Location: ' . url('index.php?controller=Auth&action=login'));
    exit;
}