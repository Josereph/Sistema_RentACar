<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== BASE_URL + url() (sin hardcodear /Sistema_RentACar) =====
if (!defined('PROJECT_ROOT_FS')) {
  define('PROJECT_ROOT_FS', realpath(__DIR__));
}

if (!defined('BASE_URL')) {
  $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
  $proj = str_replace('\\', '/', PROJECT_ROOT_FS);
  $doc  = $docRoot ? str_replace('\\', '/', $docRoot) : '';

  $rel = '';
  if ($doc && strpos($proj, $doc) === 0) {
    $rel = substr($proj, strlen($doc));
  }
  $rel = '/' . trim(str_replace('\\', '/', $rel), '/');
  if ($rel === '/') { $rel = ''; }

  define('BASE_URL', $rel);
}

if (!function_exists('url')) {
  function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
  }
}

// ===== ROUTER =====
$view = $_GET['v'] ?? 'home'; // <- Home por defecto

$views = [
  // Publico (Reserva & Catálogo)
  'home'     => __DIR__ . '/views/ReservaCatalogo/views/home.php',
  'catalogo' => __DIR__ . '/views/ReservaCatalogo/views/catalogo.php',
  'reservas' => __DIR__ . '/views/ReservaCatalogo/views/reservas.php',
  'admin_rc' => __DIR__ . '/views/ReservaCatalogo/views/admin.php',

  // Admin del sistema (lo veremos después)
  'clientes'       => __DIR__ . '/views/AdministracionClientesOperaciones/clientes.php',
  'checklist'      => __DIR__ . '/views/AdministracionClientesOperaciones/checklist.php',
  'devolucion'     => __DIR__ . '/views/AdministracionClientesOperaciones/devolucion.php',
  'disponibilidad' => __DIR__ . '/views/AdministracionClientesOperaciones/disponibilidad.php',
];

// Cargar vista
if (isset($views[$view]) && file_exists($views[$view])) {
  require $views[$view];
  exit;
}

http_response_code(404);
echo "Vista no encontrada.";