<?php
// views/ReservaCatalogo/views/pagos.php

// Definir APP_ROOT y función asset si no existen (para usar enlaces)
if (!defined('APP_ROOT')) define('APP_ROOT', '/Sistema_RentACar');
if (!function_exists('asset')) {
    function asset($path = '') { return APP_ROOT . '/' . ltrim($path, '/'); }
}

// Incluir conexión a base de datos (ajusta la ruta según tu proyecto)
require_once __DIR__ . '/../../../config/db.php';

$pdo = Database::connect();

// Obtener ID de la reserva desde la URL
$reserva_id = isset($_GET['reserva_id']) ? (int)$_GET['reserva_id'] : 0;
if ($reserva_id <= 0) {
    die('ID de reserva no válido');
}

// Obtener datos de la reserva (necesitamos el monto total)
$stmt = $pdo->prepare("SELECT * FROM tbReservas WHERE id_reserva = ?");
$stmt->execute([$reserva_id]);
$reserva = $stmt->fetch();

if (!$reserva) {
    die('Reserva no encontrada');
}

$total = $reserva['precio_total']; // Este es el monto que se debe pagar
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago | GO CAR</title>
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
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-[#1c2a38] p-8 rounded-xl border border-[#233648] max-w-md w-full">
            <h1 class="text-2xl font-bold mb-6 text-center">Pago de reserva #<?= $reserva_id ?></h1>
            <p class="text-[#92adc9] text-center mb-6">Monto a pagar: <span class="text-white font-bold text-xl">$<?= number_format($total, 2) ?></span></p>

            <form method="POST" action="procesar_pago.php" class="space-y-6">
                <!-- Campos ocultos con los datos necesarios -->
                <input type="hidden" name="reserva_id" value="<?= $reserva_id ?>">
                <input type="hidden" name="monto" value="<?= $total ?>">
                <input type="hidden" name="metodo_pago" value="tarjeta_credito"> <!-- Siempre tarjeta -->

                <!-- Campos de tarjeta (solo diseño, no funcionales) -->
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Número de tarjeta</label>
                        <input type="text" placeholder="1234 5678 9012 3456" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary" value="4111 1111 1111 1111">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Fecha vencimiento</label>
                            <input type="text" placeholder="MM/AA" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary" value="12/25">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">CVV</label>
                            <input type="text" placeholder="123" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary" value="123">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Nombre en la tarjeta</label>
                        <input type="text" placeholder="JUAN PEREZ" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary" value="CLIENTE PRUEBA">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary/90 py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/20 transition-all">
                    Pagar $<?= number_format($total, 2) ?>
                </button>
            </form>

            <div class="mt-6 text-center text-[10px] text-[#445566]">
                <span class="material-symbols-outlined text-sm align-middle">security</span>
                Datos ficticios, no se procesa realmente el pago.
            </div>
        </div>
    </div>
</body>
</html>