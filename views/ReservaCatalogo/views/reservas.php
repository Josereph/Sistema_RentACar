<!DOCTYPE html>
<html lang="es">

<?php
// ============================================
// DATOS DE VEHÍCULOS 
// ============================================
$vehiculos = [
    'toyota-corolla' => [
        'nombre' => 'Toyota Corolla',
        'subtitulo' => 'Sedán Compacto • 2022',
        'precio' => 85,
        'badge' => 'SEDAN',
        'badgeColor' => '#137fec',
        'rating' => 4.7,
        'reviews' => 892,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '169 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'CVT'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '5 Adultos'],
        ],
        'amenidades' => [
            'Toyota Safety Sense',
            'Apple CarPlay / Android Auto',
            'Control de crucero adaptativo',
            'Alerta de cambio de carril',
            'Conectividad Bluetooth',
            'Cámara de reversa',
        ],
        'imagenes' => [
            'principal' => 'https://www.motortrend.com/uploads/2021/06/2022-Toyota-Corolla-SE-exterior-front-three-quarter.jpg',
            'thumbnails' => [
                ['src' => 'https://www.motortrend.com/uploads/2021/06/2022-Toyota-Corolla-SE-exterior-front-three-quarter.jpg', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2021/06/2022-Toyota-Corolla-SE-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2021/06/2022-Toyota-Corolla-SE-exterior-rear-three-quarter.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2021/06/2022-Toyota-Corolla-SE-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'toyota-rav4' => [
        'nombre' => 'Toyota RAV4',
        'subtitulo' => 'SUV Compacto • 2023',
        'precio' => 110,
        'badge' => 'SUV',
        'badgeColor' => '#ff6b35',
        'rating' => 4.8,
        'reviews' => 1203,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '203 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '5 Adultos'],
        ],
        'amenidades' => [
            'Tracción en las 4 ruedas (AWD)',
            'Toyota Safety Sense 2.0',
            'Apple CarPlay / Android Auto',
            'Climatización de doble zona',
            'Portón eléctrico',
            'Barras de techo',
        ],
        'imagenes' => [
            'principal' => 'https://www.onlineauto.com.au/contentAsset/image/c5b285b0-bba4-4fc0-bfc7-f04b58346764',
            'thumbnails' => [
                ['src' => 'https://www.motortrend.com/uploads/2022/07/2023-Toyota-RAV4-XSE-Hybrid-front-three-quarter.jpg', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2022/07/2023-Toyota-RAV4-XSE-Hybrid-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2022/07/2023-Toyota-RAV4-XSE-Hybrid-rear-three-quarter.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2022/07/2023-Toyota-RAV4-XSE-Hybrid-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'toyota-hilux' => [
        'nombre' => 'Toyota Hilux',
        'subtitulo' => 'Pickup • 2023',
        'precio' => 130,
        'badge' => 'PICKUP',
        'badgeColor' => '#e74c3c',
        'rating' => 4.6,
        'reviews' => 756,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '204 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Diésel'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '5 Adultos'],
        ],
        'amenidades' => [
            'Tracción 4x4 (4WD)',
            'Control de crucero',
            'Pantalla táctil multimedia',
            'Bloqueo de diferencial trasero',
            'Suspensión reforzada',
            'Barra de remolque incluida',
        ],
        'imagenes' => [
            'principal' => 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/2023-Toyota-Hilux-SR5-Ute-White-1001x565-(1).jpg',
            'thumbnails' => [
                ['src' => 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/2023-Toyota-Hilux-SR5-Ute-White-1001x565-(1).jpg', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2023/03/2024-Toyota-Hilux-GR-Sport-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2023/03/2024-Toyota-Hilux-GR-Sport-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2023/03/2024-Toyota-Hilux-GR-Sport-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'kia-picanto' => [
        'nombre' => 'Kia Picanto 2006',
        'subtitulo' => 'Compacto • 2006',
        'precio' => 50,
        'badge' => 'ECONÓMICO',
        'badgeColor' => '#27ae60',
        'rating' => 4.2,
        'reviews' => 345,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '65 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Manual'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '4 Adultos'],
        ],
        'amenidades' => [
            'Aire acondicionado',
            'Dirección asistida',
            'Cierre centralizado',
            'Radio / Reproductor CD',
            'Bajo consumo de combustible',
            'Estacionamiento compacto urbano',
        ],
        'imagenes' => [
            'principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEyeJjtl1_TawqSyTzJe2CDNIXyTpotGsTGQ&s',
            'thumbnails' => [
                ['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEyeJjtl1_TawqSyTzJe2CDNIXyTpotGsTGQ&s', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/sites/5/2020/01/2006-Kia-Picanto-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/sites/5/2020/01/2006-Kia-Picanto-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/sites/5/2020/01/2006-Kia-Picanto-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'crossover' => [
        'nombre' => 'Crossover',
        'subtitulo' => 'Crossover • 2023',
        'precio' => 95,
        'badge' => 'CROSSOVER',
        'badgeColor' => '#9b59b6',
        'rating' => 4.5,
        'reviews' => 620,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '180 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '5 Adultos'],
        ],
        'amenidades' => [
            'Control de crucero adaptativo',
            'Asistente de mantenimiento de carril',
            'Apple CarPlay / Android Auto',
            'Techo solar panorámico',
            'Encendido por botón',
            'Sensores de estacionamiento traseros',
        ],
        'imagenes' => [
            'principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnpDvvAcYQZRoI0xYMsqetcUbR4n-lPLc_aQ&s',
            'thumbnails' => [
                ['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnpDvvAcYQZRoI0xYMsqetcUbR4n-lPLc_aQ&s', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2022/09/2023-crossover-suv-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2022/09/2023-crossover-suv-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2022/09/2023-crossover-suv-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'mini' => [
        'nombre' => 'Mini',
        'subtitulo' => 'Mini • 2023',
        'precio' => 60,
        'badge' => 'MINI',
        'badgeColor' => '#f39c12',
        'rating' => 4.4,
        'reviews' => 410,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '134 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '4 Adultos'],
        ],
        'amenidades' => [
            'Modo de conducción deportivo',
            'Pantalla táctil',
            'Conectividad Bluetooth',
            'Climatización automática',
            'Diseño compacto urbano',
            'Puertos de carga USB',
        ],
        'imagenes' => [
            'principal' => 'https://www.lacuracaonline.com/media/catalog/product/4/6/463101700017_8.jpg?optimize=medium&bg-color=255,255,255&fit=bounds&height=700&width=700&canvas=700:700',
            'thumbnails' => [
                ['src' => 'https://www.lacuracaonline.com/media/catalog/product/4/6/463101700017_8.jpg?optimize=medium&bg-color=255,255,255&fit=bounds&height=700&width=700&canvas=700:700', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2021/11/2023-MINI-Cooper-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2021/11/2023-MINI-Cooper-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2021/11/2023-MINI-Cooper-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'minivan' => [
        'nombre' => 'Minivan',
        'subtitulo' => 'Minivan • 2023',
        'precio' => 100,
        'badge' => 'FAMILIAR',
        'badgeColor' => '#3498db',
        'rating' => 4.6,
        'reviews' => 530,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '260 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '7 Adultos'],
        ],
        'amenidades' => [
            'Puertas traseras corredizas',
            'Climatización de tres zonas',
            'Sistema de entretenimiento trasero',
            'Asientos rebatibles',
            'Portón eléctrico',
            'Múltiples puertos USB',
        ],
        'imagenes' => [
            'principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAlbrymVJ7spOe0z0N2MCEyXvs8hx1lpldKw&s',
            'thumbnails' => [
                ['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAlbrymVJ7spOe0z0N2MCEyXvs8hx1lpldKw&s', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2022/04/2023-Toyota-Sienna-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2022/04/2023-Toyota-Sienna-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2022/04/2023-Toyota-Sienna-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'vehiculo-lujo' => [
        'nombre' => 'Vehículo de Lujo',
        'subtitulo' => 'Premium • 2023',
        'precio' => 200,
        'badge' => 'PREMIUM',
        'badgeColor' => '#c9a84c',
        'rating' => 4.9,
        'reviews' => 280,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '375 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '5 Adultos'],
        ],
        'amenidades' => [
            'Interior de cuero premium',
            'Suspensión adaptativa',
            'Sistema de sonido envolvente',
            'Pantalla de visualización frontal',
            'Asientos con masaje',
            'Asistente de visión nocturna',
        ],
        'imagenes' => [
            'principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWfsR6cgnaq7nMEzpl-mjoAfMJ3gCGzUhq5A&s',
            'thumbnails' => [
                ['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWfsR6cgnaq7nMEzpl-mjoAfMJ3gCGzUhq5A&s', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2022/12/2023-Mercedes-Benz-S-Class-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2022/12/2023-Mercedes-Benz-S-Class-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2022/12/2023-Mercedes-Benz-S-Class-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
    'convertible' => [
        'nombre' => 'Convertible',
        'subtitulo' => 'Convertible • 2023',
        'precio' => 150,
        'badge' => 'CONVERTIBLE',
        'badgeColor' => '#e91e63',
        'rating' => 4.7,
        'reviews' => 390,
        'specs' => [
            ['icon' => 'fa-horse-head', 'label' => 'POTENCIA', 'value' => '300 HP'],
            ['icon' => 'fa-cog', 'label' => 'TRANSMISIÓN', 'value' => 'Automática'],
            ['icon' => 'fa-gas-pump', 'label' => 'COMBUSTIBLE', 'value' => 'Gasolina'],
            ['icon' => 'fa-users', 'label' => 'ASIENTOS', 'value' => '2 Adultos'],
        ],
        'amenidades' => [
            'Techo retráctil rígido',
            'Escape deportivo',
            'Asientos deportivos con soportes laterales',
            'Deflector de viento',
            'Sistema de sonido premium',
            'Control de crucero adaptativo',
        ],
        'imagenes' => [
            'principal' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR31WbMSju4OPRvW6t4dj8upKPJJG0g01jdHg&s',
            'thumbnails' => [
                ['src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR31WbMSju4OPRvW6t4dj8upKPJJG0g01jdHg&s', 'label' => 'Exterior'],
                ['src' => 'https://www.motortrend.com/uploads/2022/08/2023-BMW-Z4-cabin.jpg', 'label' => 'Cabina'],
                ['src' => 'https://www.motortrend.com/uploads/2022/08/2023-BMW-Z4-rear.jpg', 'label' => 'Trasera'],
                ['src' => 'https://www.motortrend.com/uploads/2022/08/2023-BMW-Z4-interior.jpg', 'label' => 'Interior'],
            ],
        ],
    ],
];

// Obtener el vehículo seleccionado
$carId = isset($_GET['car']) ? $_GET['car'] : null;
$vehiculo = $carId && isset($vehiculos[$carId]) ? $vehiculos[$carId] : null;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $vehiculo ? htmlspecialchars($vehiculo['nombre']) . ' | Reserva' : 'Reservas' ?> | GO CAR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/ReservaCatalogo/style.css">
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/ReservaCatalogo/reservas.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <?php if ($vehiculo): ?>
    <!-- 
         PÁGINA DE RESERVA CON VEHÍCULO SELECCIONADO
         -->
    <section class="reservation-page py-5">
        <div class="container">
            <div class="row g-4">

                <!-- COLUMNA IZQUIERDA - Vehículo -->
                <div class="col-lg-7">

                    <!-- Galería Principal -->
                    <div class="vehicle-gallery">
                        <div class="gallery-main">
                            <span class="vehicle-badge"
                                style="background: <?= $vehiculo['badgeColor'] ?>;"><?= $vehiculo['badge'] ?></span>
                            <img src="<?= $vehiculo['imagenes']['principal'] ?>" class="gallery-main-img"
                                alt="<?= htmlspecialchars($vehiculo['nombre']) ?> - Vista principal">
                            <div class="gallery-dots">
                                <?php foreach ($vehiculo['imagenes']['thumbnails'] as $i => $thumb): ?>
                                <span class="dot <?= $i === 0 ? 'active' : '' ?>"></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="gallery-thumbnails">
                            <?php foreach ($vehiculo['imagenes']['thumbnails'] as $thumb): ?>
                            <div class="thumbnail">
                                <img src="<?= $thumb['src'] ?>" alt="<?= htmlspecialchars($thumb['label']) ?>">
                                <span class="thumbnail-label"><?= htmlspecialchars($thumb['label']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Info del Vehículo -->
                    <div class="vehicle-info">
                        <h2 class="vehicle-name"><?= htmlspecialchars($vehiculo['nombre']) ?></h2>
                        <p class="vehicle-subtitle"><?= htmlspecialchars($vehiculo['subtitulo']) ?></p>
                        <div class="vehicle-rating">
                            <i class="fas fa-star"></i>
                            <span class="rating-score"><?= $vehiculo['rating'] ?></span>
                            <span class="rating-count">(<?= number_format($vehiculo['reviews']) ?> Reseñas)</span>
                        </div>
                    </div>

                    <!-- Specs Técnicas -->
                    <div class="specs-grid">
                        <?php foreach ($vehiculo['specs'] as $spec): ?>
                        <div class="spec-item">
                            <div class="spec-icon">
                                <i class="fas <?= $spec['icon'] ?>"></i>
                            </div>
                            <div class="spec-label"><?= $spec['label'] ?></div>
                            <div class="spec-value"><?= $spec['value'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Amenidades Incluidas -->
                    <div class="amenities-section">
                        <h4 class="amenities-title">Amenidades Incluidas</h4>
                        <div class="amenities-grid">
                            <?php foreach ($vehiculo['amenidades'] as $amenidad): ?>
                            <div class="amenity-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?= htmlspecialchars($amenidad) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Calendario de Disponibilidad -->
                    <div class="calendar-section">
                        <h4 class="calendar-title">Calendario de Disponibilidad</h4>
                        <div id="calendar-container">
                            <div class="cal-nav">
                                <button type="button" class="cal-nav-btn" id="calPrev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="cal-month-label" id="calMonthLabel"></span>
                                <button type="button" class="cal-nav-btn" id="calNext">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="cal-weekdays">
                                <span>Lun</span><span>Mar</span><span>Mié</span>
                                <span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
                            </div>
                            <div class="cal-days" id="calDays">
                                <!-- JS renders days here -->
                            </div>
                            <div class="cal-legend">
                                <span class="cal-legend-item">
                                    <span class="cal-legend-dot cal-legend-available"></span> Disponible
                                </span>
                                <span class="cal-legend-item">
                                    <span class="cal-legend-dot cal-legend-unavailable"></span> No disponible
                                </span>
                                <span class="cal-legend-item">
                                    <span class="cal-legend-dot cal-legend-selected"></span> Seleccionado
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- COLUMNA DERECHA - Barra lateral de reserva -->
                <div class="col-lg-5">

                    <!-- Card de Reserva -->
                    <div class="booking-card">
                        <div class="booking-header">
                            <div class="booking-price">
                                <span class="price-amount">$<?= $vehiculo['precio'] ?></span>
                                <span class="price-period">/día</span>
                            </div>
                            <span class="instant-badge">RESERVA INSTANTÁNEA</span>
                        </div>

                        <form class="booking-form">
                            <div class="form-group">
                                <label class="form-label">UBICACIÓN DE RECOGIDA</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <select class="form-select">
                                        <option>San Francisco International</option>
                                        <option>San Salvador</option>
                                        <option>Santa Ana</option>
                                    </select>
                                </div>
                            </div>

                            <div class="date-row">
                                <div class="form-group">
                                    <label class="form-label">FECHA INICIO</label>
                                    <input type="date" class="form-control" value="2024-10-04">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">FECHA FIN</label>
                                    <input type="date" class="form-control" value="2024-10-07">
                                </div>
                            </div>

                            <div class="price-breakdown">
                                <div class="price-line">
                                    <span>$<?= $vehiculo['precio'] ?> x 3 días</span>
                                    <span>$<?= number_format($vehiculo['precio'] * 3, 2) ?></span>
                                </div>
                                <div class="price-line">
                                    <span>Seguro (Cobertura Total)</span>
                                    <span>$45.00</span>
                                </div>
                                <div class="price-line">
                                    <span>Tarifa de servicio</span>
                                    <span>$12.50</span>
                                </div>
                                <div class="price-total">
                                    <span>Total</span>
                                    <span
                                        class="total-amount">$<?= number_format(($vehiculo['precio'] * 3) + 45 + 12.50, 2) ?></span>
                                </div>
                            </div>

                            <button type="button" class="btn-proceed" id="btnOpenPayment">
                                Proceder al Pago <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>

                        <div class="secure-notice">
                            <i class="fas fa-lock"></i>
                            <span>Pagos seguros y datos encriptados</span>
                        </div>
                        <p class="terms-text">
                            Al proceder aceptas nuestros <a href="#">Términos de servicio</a> y <a href="#">Políticas de
                                reembolso</a>.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!--
         MODAL DE PAGO
         -->
    <div class="payment-overlay" id="paymentOverlay">
        <div class="payment-modal">

            <!-- Paso 1: Formulario de pago -->
            <div class="payment-step" id="paymentStep1">
                <div class="payment-modal-header">
                    <h3>Completa tu Pago</h3>
                    <button class="payment-close" id="btnClosePayment">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Resumen del pedido -->
                <div class="payment-summary">
                    <div class="payment-summary-row">
                        <span class="payment-summary-label">Vehículo</span>
                        <span class="payment-summary-value"><?= htmlspecialchars($vehiculo['nombre']) ?></span>
                    </div>
                    <div class="payment-summary-row">
                        <span class="payment-summary-label">Tarifa Diaria</span>
                        <span class="payment-summary-value">$<?= $vehiculo['precio'] ?></span>
                    </div>
                    <div class="payment-summary-row payment-summary-total">
                        <span>Total</span>
                        <span>$<?= number_format(($vehiculo['precio'] * 3) + 45 + 12.50, 2) ?></span>
                    </div>
                </div>

                <!-- Tabs de método de pago -->
                <div class="payment-tabs">
                    <button class="payment-tab active" data-method="card">
                        <i class="fas fa-credit-card"></i> Tarjeta
                    </button>
                    <button class="payment-tab" data-method="paypal">
                        <i class="fab fa-paypal"></i> PayPal
                    </button>
                    <button class="payment-tab" data-method="cash">
                        <i class="fas fa-money-bill-wave"></i> Efectivo
                    </button>
                </div>

                <!-- Formulario: Tarjeta de Crédito -->
                <div class="payment-form" id="formCard">
                    <div class="pf-group">
                        <label>Número de Tarjeta</label>
                        <div class="pf-input-icon">
                            <i class="fas fa-credit-card"></i>
                            <input type="text" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                    </div>
                    <div class="pf-row">
                        <div class="pf-group">
                            <label>Fecha de Vencimiento</label>
                            <input type="text" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="pf-group">
                            <label>CVV</label>
                            <div class="pf-input-icon">
                                <i class="fas fa-lock"></i>
                                <input type="text" placeholder="123" maxlength="4">
                            </div>
                        </div>
                    </div>
                    <div class="pf-group">
                        <label>Nombre del Titular</label>
                        <input type="text" placeholder="Juan Pérez">
                    </div>
                </div>

                <!-- Formulario: PayPal -->
                <div class="payment-form" id="formPaypal" style="display:none;">
                    <div class="pf-group">
                        <label>Correo de PayPal</label>
                        <div class="pf-input-icon">
                            <i class="fab fa-paypal"></i>
                            <input type="email" placeholder="you@email.com">
                        </div>
                    </div>
                    <p class="pf-note">Serás redirigido a PayPal para completar el pago.</p>
                </div>

                <!-- Formulario: Cash -->
                <div class="payment-form" id="formCash" style="display:none;">
                    <div class="cash-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <p><strong>Pago en Recogida</strong></p>
                            <p>Paga el monto total en efectivo al recoger el vehículo en la ubicación seleccionada.
                                Puede aplicarse una tarifa de retención.</p>
                        </div>
                    </div>
                </div>

                <button class="btn-confirm-payment" id="btnConfirmPayment">
                    <i class="fas fa-lock"></i> Confirmar Pago —
                    $<?= number_format(($vehiculo['precio'] * 3) + 45 + 12.50, 2) ?>
                </button>
            </div>

            <!-- Paso 2: Éxito -->
            <div class="payment-step" id="paymentStep2" style="display:none;">
                <div class="payment-success">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3>¡Pago Exitoso!</h3>
                    <p>Tu reserva para <strong><?= htmlspecialchars($vehiculo['nombre']) ?></strong> ha sido confirmada.
                    </p>
                    <div class="success-details">
                        <div class="success-detail-row">
                            <span>ID de Reserva</span>
                            <span id="reservationId">#RC-0000</span>
                        </div>
                        <div class="success-detail-row">
                            <span>Monto Pagado</span>
                            <span>$<?= number_format(($vehiculo['precio'] * 3) + 45 + 12.50, 2) ?></span>
                        </div>
                    </div>
                    <button class="btn-confirm-payment" id="btnCloseSuccess">
                        <i class="fas fa-check"></i> Listo
                    </button>
                </div>
            </div>

        </div>
    </div>

    <?php else: ?>
    <!-- 
         PÁGINA SIN VEHÍCULO SELECCIONADO
         -->
    <section class="reservation-page py-5">
        <div class="container">
            <div class="no-vehicle-selected">
                <div class="no-vehicle-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h2>No has seleccionado un vehículo</h2>
                <p>Para hacer una reserva, primero elige un vehículo de nuestro catálogo.</p>
                <a href="/Sistema_RentACar/views/ReservaCatalogo/views/catalogo.php" class="btn-go-catalog">
                    <i class="fas fa-th-large"></i> Ir al Catálogo
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Sistema_RentACar/assets/js/ReservaCatalogo/script.js"></script>
</body>

</html>