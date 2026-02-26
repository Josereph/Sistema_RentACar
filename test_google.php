<?php
require __DIR__ . '/vendor/autoload.php';

echo "Autoload cargado.<br>";

if (class_exists('Google_Client')) {
    echo "Google_Client existe (sin namespace).";
} elseif (class_exists('Google\\Client')) {
    echo "Google\\Client existe (con namespace).";
} else {
    echo "No se encuentra la clase Google_Client ni Google\\Client.";
}