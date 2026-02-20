<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * BASE_URL: sirve para que /Sistema_RentACar, /Rent-a-car, etc. funcionen igual.
 * Ej: si entras por http://localhost/Sistema_RentACar/index.php
 * BASE_URL = /Sistema_RentACar
 */
$BASE_URL = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($BASE_URL === '/' || $BASE_URL === '\\') $BASE_URL = '';

$view = $_GET['v'] ?? 'clientes';

$views = [
  'clientes'       => __DIR__ . '/views/AdministracionClientesOperaciones/clientes.php',
  'checklist'      => __DIR__ . '/views/AdministracionClientesOperaciones/checklist.php',
  'devolucion'     => __DIR__ . '/views/AdministracionClientesOperaciones/devolucion.php',
  'disponibilidad' => __DIR__ . '/views/AdministracionClientesOperaciones/disponibilidad.php',
];

if (isset($views[$view]) && file_exists($views[$view])) {
  require $views[$view];
} else {
  http_response_code(404);
  echo "Vista no encontrada.";
}