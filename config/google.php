<?php
// config/google.php
defined('PROJECT_ROOT_FS') or define('PROJECT_ROOT_FS', dirname(__DIR__));

// Cargar variables de entorno desde .env si existe
if (file_exists(PROJECT_ROOT_FS . '/.env')) {
    $lines = file(PROJECT_ROOT_FS . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}


define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', 'http://localhost/Sistema_RentACar/index.php?controller=GoogleAuth&action=callback');