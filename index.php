<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * ==========================================================
 *  BOOTSTRAP / ROUTER PRINCIPAL
 *  - Define PROJECT_ROOT_FS
 *  - Calcula BASE_URL automáticamente
 *  - Define url() para rutas de assets/enlaces
 *  - Soporta:
 *      A) index.php?v=home  (tu sistema viejo)
 *      B) index.php?controller=Administracion&action=index (MVC controllers)
 * ==========================================================
 */

// 1) Root físico del proyecto
define('PROJECT_ROOT_FS', __DIR__);

// 2) Base URL automática (sin hardcodear /Sistema_RentACar)
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);  // ej: /Sistema_RentACar/index.php
$baseDir = rtrim(str_replace('/index.php', '', $scriptName), '/'); // ej: /Sistema_RentACar
define('BASE_URL', $baseDir);

// 3) Helper URL
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $path;
}

/**
 * ==========================================================
 *  ROUTE: controller/action (MVC)
 * ==========================================================
 */
$controller = $_GET['controller'] ?? null;
$action     = $_GET['action'] ?? null;

if ($controller && $action) {
    $controllerName = $controller . 'Controller';

    // Busca el controller en tus carpetas reales
    $possiblePaths = [
        PROJECT_ROOT_FS . '/controller/shared/' . $controllerName . '.php',
        PROJECT_ROOT_FS . '/controller/AdministracionClientesOperaciones/' . $controllerName . '.php',
        PROJECT_ROOT_FS . '/controller/OperacionesRentaControl/' . $controllerName . '.php',
        PROJECT_ROOT_FS . '/controller/ReservaCatalogo/' . $controllerName . '.php',
        PROJECT_ROOT_FS . '/controller/FlotaDisponibilidadAcceso/' . $controllerName . '.php',
    ];

    $foundFile = null;
    foreach ($possiblePaths as $p) {
        if (file_exists($p)) { $foundFile = $p; break; }
    }

    if (!$foundFile) {
        http_response_code(404);
        die("Controller file no encontrado: " . htmlspecialchars($controllerName));
    }

    require_once $foundFile;

    if (!class_exists($controllerName)) {
        http_response_code(500);
        die("Clase controller no existe: " . htmlspecialchars($controllerName));
    }

    $obj = new $controllerName();

    if (!method_exists($obj, $action)) {
        http_response_code(404);
        die("Action no existe: " . htmlspecialchars($action));
    }

    $obj->$action();
    exit; // IMPORTANTÍSIMO: no seguir con ?v=
}

/**
 * ==========================================================
 *  ROUTE: v (tu sistema viejo de vistas)
 * ==========================================================
 */
$v = $_GET['v'] ?? 'home';

switch ($v) {
    case 'home':
        require PROJECT_ROOT_FS . '/views/ReservaCatalogo/views/home.php';
        break;

    case 'catalogo':
        require PROJECT_ROOT_FS . '/views/ReservaCatalogo/views/catalogo.php';
        break;

    case 'reservas':
        require PROJECT_ROOT_FS . '/views/ReservaCatalogo/views/reservas.php';
        break;

    // Si quieres una vista admin vieja por v, puedes agregarla, pero ya no hace falta.
    // case 'admin': require PROJECT_ROOT_FS . '/views/ReservaCatalogo/views/admin.php'; break;

    default:
        http_response_code(404);
        echo "Vista no encontrada: " . htmlspecialchars($v);
        break;
}