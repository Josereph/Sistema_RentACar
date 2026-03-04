<?php
// views/ReservaCatalogo/views/pago_exitoso.php

if (!defined('APP_ROOT')) define('APP_ROOT', '/Sistema_RentACar');
if (!function_exists('asset')) {
    function asset($path = '') { return APP_ROOT . '/' . ltrim($path, '/'); }
}

$reserva_id = isset($_GET['reserva_id']) ? (int)$_GET['reserva_id'] : 0;
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso | GO CAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#137fec',
                        'background-dark': '#101922',
                    },
                    fontFamily: {
                        display: ['Manrope', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-background-dark font-display text-white">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-[#1c2a38] p-8 rounded-xl border border-[#233648] max-w-md w-full text-center">
            <div class="text-green-500 mb-4">
                <span class="material-symbols-outlined text-6xl">check_circle</span>
            </div>
            <h1 class="text-2xl font-bold mb-2">¡Pago confirmado!</h1>
            <p class="text-[#92adc9] mb-6">
                Tu reserva #<?= $reserva_id ?> ha sido confirmada. Recibirás un correo con los detalles.
            </p>
            
            <!-- Botón que lleva a home.php (ajusta la ruta si es necesario) -->
            <a href="<?= asset('views/ReservaCatalogo/views/home.php') ?>" 
               class="inline-block bg-primary hover:bg-primary/90 px-6 py-3 rounded-lg font-bold transition-colors w-full">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>