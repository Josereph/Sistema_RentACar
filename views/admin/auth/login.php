<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCar - Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/admin/login.css">
</head>
<body>

<canvas id="particles"></canvas>

<main class="main">
    <div class="split">
        <div class="hero" id="hero">
            <div class="hero-image" id="heroImage"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1 class="hero-title">Tu viaje<br>comienza aquí.</h1>
                <p class="hero-subtitle">Experimenta el servicio premium de alquiler de vehículos para tu próxima aventura. Accede a nuestra flota exclusiva con solo unos clics.</p>
                <div class="hero-badges">
                    <div class="badge">
                        <span class="material-symbols-outlined">verified_user</span>
                        <span>Reserva segura</span>
                    </div>
                    <div class="badge">
                        <span class="material-symbols-outlined">distance</span>
                        <span>Kilometraje ilimitado</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Go Car Rent A Car</h2>
                    <p>Tu mejor opción para alquilar</p>
                </div>

                <!-- Formulario para clientes -->
                <form action="/Sistema_RentACar/index.php?area=admin&controller=Auth&action=login" method="POST">
                    <div class="input-group">
                        <label class="input-label">Correo electrónico</label>
                        <div class="input-wrapper">
                            <span class="material-symbols-outlined input-icon">mail</span>
                            <input type="email" name="correo" class="input-field" placeholder="nombre@empresa.com" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Contraseña</label>
                        <div class="input-wrapper">
                            <span class="material-symbols-outlined input-icon">lock</span>
                            <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Continuar</button>
                </form>

                <div class="divider">
                    <span class="divider-line"></span>
                    <span>O continúa con</span>
                    <span class="divider-line"></span>
                </div>

                <a href="/Sistema_RentACar/index.php?area=cliente&controller=GoogleAuth&action=login" class="google-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335"></path>
                    </svg>
                    <span>Continuar con Google</span>
                </a>

                <div class="legal">
                    Al continuar, aceptas nuestros Términos y Política de Privacidad.
                </div>

                <div class="text-center mt-4 text-sm text-[#92adc9]">
                    ¿No tienes cuenta? 
                    <a href="/Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=registro" class="text-primary hover:underline">
                        Regístrate
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="/Sistema_RentACar/assets/js/admin/login.js"></script>
</body>
</html>