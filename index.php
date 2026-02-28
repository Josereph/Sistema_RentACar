<?php
// index.php - Router principal
session_start();

define('PROJECT_ROOT_FS', __DIR__);
define('BASE_URL', '/Sistema_RentACar'); // Ajusta si tu proyecto está en otra ruta

if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

// Determinar el área (admin, empleado, cliente)
$area = $_GET['area'] ?? 'admin'; // por defecto admin

$controller = $_GET['controller'] ?? null;
$action     = $_GET['action']     ?? 'index';
$vista      = $_GET['v']           ?? null; // para vistas antiguas (solo admin)

// ============================================
// MAPEO DE CONTROLADORES POR ÁREA
// ============================================

// Administradores (y superadmin)
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

// Empleados (rol operador)
$empleadoControllers = [
    'Auth'               => 'AuthController', // mismo que admin
    'GoogleAuth'         => 'GoogleAuthController',
    'DashboardEmpleado'  => 'DashboardEmpleadoController',
    'ClientesEmpleado'   => 'ClientesEmpleadoController',
    'VehiculosEmpleado'  => 'VehiculosEmpleadoController',
    'ReservasEmpleado'   => 'ReservasEmpleadoController',
    'DevolucionEmpleado' => 'DevolucionEmpleadoController',
    'ChecklistEmpleado'  => 'ChecklistEmpleadoController',
    'MantenimientosEmpleado' => 'MantenimientosEmpleadoController',
    'PerfilEmpleado'     => 'PerfilEmpleadoController',
    'AsistenciaAdmin'    => 'AsistenciaAdminController',
    'ContratosEmpleado' => 'ContratosEmpleadoController',
    'DevolucionEmpleado' => 'DevolucionEmpleadoController',
'ChecklistEmpleado'  => 'ChecklistEmpleadoController',
    
];

// Clientes (rol cliente)
$clienteControllers = [
    'Auth'          => 'AuthClienteController',
    'GoogleAuth'    => 'GoogleAuthController',
    'Catalogo'      => 'CatalogoController',
    'Reservas'      => 'ReservasClienteController',
    'Perfil'        => 'PerfilClienteController',
];

// ============================================
// ÁREA ADMIN
// ============================================
if ($area === 'admin') {
    // Si no hay controlador ni vista, redirigir al login o dashboard
    if (!$controller && !$vista) {
        if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
            header('Location: ' . url('index.php?controller=Dashboard&action=index'));
            exit;
        } else {
            header('Location: ' . url('index.php?controller=Auth&action=login'));
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
        header('Location: ' . url('index.php?controller=Auth&action=login'));
        exit;
    }
}
// ============================================
// ÁREA EMPLEADO
// ============================================
elseif ($area === 'empleado') {
    // Verificar sesión de empleado (rol operador)
    if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true || $_SESSION['admin_rol'] !== 'operador') {
        header('Location: ' . url('index.php?controller=Auth&action=login'));
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
    // Aquí asumimos que los clientes tienen su propia sesión (cliente_logged)
    if (!isset($_SESSION['cliente_logged']) || $_SESSION['cliente_logged'] !== true) {
        header('Location: ' . url('index.php?area=cliente&controller=Auth&action=login'));
        exit;
    }

    if (!$controller) {
        header('Location: ' . url('index.php?area=cliente&controller=Catalogo&action=index'));
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
// ÁREA DESCONOCIDA
// ============================================
else {
    die("Área no válida.");
}
?>