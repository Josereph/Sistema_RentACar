<?php
// index.php - Punto de entrada principal

session_start();

// Definir constantes
define('PROJECT_ROOT_FS', __DIR__);
define('BASE_URL', '/Sistema_RentACar'); // Ajusta si tu proyecto está en otra ruta

if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// ============================================
// DETECCIÓN DE PARÁMETROS DE RUTA
// ============================================
$area      = $_GET['area'] ?? null;
$controller = $_GET['controller'] ?? null;
$action     = $_GET['action'] ?? 'index';
$vista      = $_GET['v'] ?? null;

// ============================================
// MAPEO DE CONTROLADORES POR ÁREA
// ============================================
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

$empleadoControllers = [
    'Auth'               => 'AuthController',
    'GoogleAuth'         => 'GoogleAuthController',
    'DashboardEmpleado'  => 'DashboardEmpleadoController',
    'ClientesEmpleado'   => 'ClientesEmpleadoController',
    'VehiculosEmpleado'  => 'VehiculosEmpleadoController',
    'ReservasEmpleado'   => 'ReservasEmpleadoController',
    'DevolucionEmpleado' => 'DevolucionEmpleadoController',
    'ChecklistEmpleado'  => 'ChecklistEmpleadoController',
    'MantenimientosEmpleado' => 'MantenimientosEmpleadoController',
    'PerfilEmpleado'     => 'PerfilEmpleadoController',   // ← Agregado
    'ContratosEmpleado'  => 'ContratosEmpleadoController',
];

$clienteControllers = [
    'Auth'          => 'AuthClienteController',
    'GoogleAuth'    => 'GoogleAuthController',
    'Catalogo'      => 'CatalogoController',
    'Reservas'      => 'ReservasClienteController',
    'Perfil'        => 'PerfilClienteController',
    'Cliente'       => 'ClienteController',
];

// ============================================
// INTELIGENCIA DE RUTAS: Si no hay área pero sí controlador, deducir por sesión
// ============================================
if (!$area && $controller) {
    if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
        // Es admin/empleado
        if (isset($adminControllers[$controller])) {
            $area = 'admin';
        } elseif ($_SESSION['admin_rol'] === 'operador' && isset($empleadoControllers[$controller])) {
            $area = 'empleado';
        }
    } elseif (isset($_SESSION['cliente_logged']) && $_SESSION['cliente_logged'] === true) {
        // Es cliente
        if (isset($clienteControllers[$controller])) {
            $area = 'cliente';
        }
    }
    // Si no se pudo determinar, $area sigue siendo null y se manejará después
}

// ============================================
// SI NO HAY PARÁMETROS, REDIRIGIR SEGÚN SESIÓN
// ============================================
if (!$area && !$controller && !$vista) {
    // No se pidió ninguna ruta específica
    if (isset($_SESSION['cliente_logged']) && $_SESSION['cliente_logged'] === true) {
        // Cliente logueado: ir a home del cliente
        header('Location: ' . url('views/ReservaCatalogo/views/home.php'));
        exit;
    } else {
        // Visitante: mostrar página de bienvenida pública
        include __DIR__ . '/views/public/welcome.php';
        exit;
    }
}

// ============================================
// SI AÚN NO HAY ÁREA, PERO HAY CONTROLADOR O VISTA, REDIRIGIR A LOGIN (SEGURIDAD)
// ============================================
if (!$area && ($controller || $vista)) {
    // No se pudo determinar el área, probablemente falta autenticación
    header('Location: ' . url('index.php?area=admin&controller=Auth&action=login'));
    exit;
}

// ============================================
// ÁREA ADMIN
// ============================================
if ($area === 'admin') {
    // Si no hay controlador, redirigir al dashboard o login
    if (!$controller) {
        if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
            header('Location: ' . url('index.php?area=admin&controller=Dashboard&action=index'));
            exit;
        } else {
            header('Location: ' . url('index.php?area=admin&controller=Auth&action=login'));
            exit;
        }
    }

    if (isset($adminControllers[$controller])) {
        $archivo = __DIR__ . "/controller/Admin/{$adminControllers[$controller]}.php";
        if (file_exists($archivo)) {
            require_once $archivo;
            $obj = new $adminControllers[$controller]();
            if (method_exists($obj, $action)) {
                $obj->$action();
            } else {
                die("Acción no encontrada en el controlador admin.");
            }
        } else {
            die("Controlador admin no encontrado: " . $archivo);
        }
    } elseif ($vista) {
        $archivoVista = __DIR__ . "/views/AdministracionClientesOperaciones/{$vista}.php";
        if (file_exists($archivoVista)) {
            require $archivoVista;
        } else {
            die("Vista no encontrada.");
        }
    } else {
        header('Location: ' . url('index.php?area=admin&controller=Auth&action=login'));
        exit;
    }
}

// ============================================
// ÁREA EMPLEADO
// ============================================
elseif ($area === 'empleado') {
    // Verificar sesión de empleado: debe tener admin_logged y rol 'operador'
    if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true || $_SESSION['admin_rol'] !== 'operador') {
        header('Location: ' . url('index.php?area=admin&controller=Auth&action=login'));
        exit;
    }

    if (!$controller) {
        header('Location: ' . url('index.php?area=empleado&controller=DashboardEmpleado&action=index'));
        exit;
    }

    if (isset($empleadoControllers[$controller])) {
        $archivo = __DIR__ . "/controller/Empleado/{$empleadoControllers[$controller]}.php";
        if (file_exists($archivo)) {
            require_once $archivo;
            $obj = new $empleadoControllers[$controller]();
            if (method_exists($obj, $action)) {
                $obj->$action();
            } else {
                die("Acción no encontrada en el controlador de empleado.");
            }
        } else {
            die("Controlador de empleado no encontrado: " . $archivo);
        }
    } else {
        die("Controlador no válido para empleado.");
    }
}
// ============================================
// ÁREA CLIENTE
// ============================================
elseif ($area === 'cliente') {
    // Acciones que no requieren sesión
    $accionesPublicas = ['login', 'registro', 'registrar', 'callback'];

    // Si la acción NO es pública y el usuario no está logueado, redirigir al login
    if (!in_array($action, $accionesPublicas) && (!isset($_SESSION['cliente_logged']) || $_SESSION['cliente_logged'] !== true)) {
        header('Location: ' . url('index.php?area=cliente&controller=Auth&action=login'));
        exit;
    }

    // Si no se especifica controlador, redirigir según sesión
    if (!$controller) {
        if (isset($_SESSION['cliente_logged']) && $_SESSION['cliente_logged'] === true) {
            header('Location: ' . url('index.php?area=cliente&controller=Catalogo&action=index'));
        } else {
            header('Location: ' . url('index.php?area=cliente&controller=Auth&action=login'));
        }
        exit;
    }

    if (isset($clienteControllers[$controller])) {
        $archivo = __DIR__ . "/controller/Cliente/{$clienteControllers[$controller]}.php";
        if (file_exists($archivo)) {
            require_once $archivo;
            $obj = new $clienteControllers[$controller]();
            if (method_exists($obj, $action)) {
                $obj->$action();
            } else {
                die("Acción no encontrada en el controlador de cliente.");
            }
        } else {
            die("Controlador de cliente no encontrado: " . $archivo);
        }
    } else {
        die("Controlador no válido para cliente.");
    }
}
// ============================================
// ÁREA NO VÁLIDA
// ============================================
else {
    // Si se proporcionó un área no válida o ningún área pero sí otros parámetros
    header('Location: ' . url(''));
    exit;
}