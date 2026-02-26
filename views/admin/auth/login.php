<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoCar - Iniciar Sesión</title>
    <!-- Google Fonts y Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: #101922;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Bar - estilo minimalista */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(16,25,34,0.8);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 2rem;
            height: 2rem;
            color: #137fec;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
        }

        .contact-btn {
            background-color: #137fec;
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(19,127,236,0.3);
        }

        .contact-btn:hover {
            background-color: #0e64b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(19,127,236,0.4);
        }

        /* Main container */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 4rem; /* espacio para navbar fijo */
            padding: 2rem;
        }

        /* Split screen layout */
        .split {
            display: flex;
            width: 100%;
            max-width: 1400px;
            min-height: 650px;
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            background-color: rgba(16,25,34,0.6);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* Left side - hero image with parallax effect */
        .hero {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: none;
        }

        @media (min-width: 1024px) {
            .hero {
                display: block;
            }
        }

        .hero-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1494976388531-d1058494cdd8?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            transition: transform 0.2s ease-out;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(16,25,34,0.9) 0%, rgba(19,127,236,0.3) 100%);
        }

        .hero-content {
            position: absolute;
            bottom: 4rem;
            left: 4rem;
            right: 4rem;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
            max-width: 500px;
        }

        .hero-badges {
            display: flex;
            gap: 2rem;
        }

        .badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        .badge .material-symbols-outlined {
            font-size: 1.2rem;
        }

        /* Right side - login form */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: rgba(16,25,34,0.7);
            backdrop-filter: blur(15px);
            border-left: 1px solid rgba(255,255,255,0.05);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .form-header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff, #137fec);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-header p {
            color: rgba(255,255,255,0.6);
            font-size: 0.95rem;
        }

        /* Estilos de inputs con glassmorphism */
        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.4);
            transition: color 0.2s ease;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem;
            font-size: 1rem;
            color: #fff;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #137fec;
            background: rgba(19,127,236,0.1);
            box-shadow: 0 0 0 4px rgba(19,127,236,0.2);
        }

        .input-field:focus + .input-icon {
            color: #137fec;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #137fec;
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #137fec;
        }

        /* Botón principal con efecto */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #137fec, #0e64b9);
            border: none;
            border-radius: 1rem;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            box-shadow: 0 8px 20px rgba(19,127,236,0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(19,127,236,0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        /* Botón de Google con hover sutil */
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .google-btn:hover {
            background: rgba(19,127,236,0.15);
            border-color: #137fec;
            transform: translateY(-2px);
        }

        /* Footer legal */
        .legal {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
        }

        .legal a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .legal a:hover {
            color: #137fec;
        }

        .register-link {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }

        .register-link a {
            color: #137fec;
            text-decoration: none;
            font-weight: 600;
            position: relative;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #137fec;
            transition: width 0.3s ease;
        }

        .register-link a:hover::after {
            width: 100%;
        }

        /* Footer inferior */
        .page-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2.5rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(16,25,34,0.8);
            backdrop-filter: blur(10px);
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
        }

        .footer-links {
            display: flex;
            gap: 2rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #137fec;
        }

        /* Efecto de partículas sutiles (opcional) */
        #particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>

<!-- Canvas para partículas (opcional) -->
<canvas id="particles"></canvas>

<!-- Navbar fijo -->
<nav class="navbar">
    <div class="logo">
        <div class="logo-icon">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clipPath="url(#clip0_6_330)">
                    <path d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor"/>
                </g>
                <defs>
                    <clipPath id="clip0_6_330"><rect width="48" height="48" fill="white"/></clipPath>
                </defs>
            </svg>
        </div>
        <span class="logo-text">GoCar</span>
    </div>
    <button class="contact-btn">Contacto</button>
</nav>

<!-- Contenido principal -->
<main class="main">
    <div class="split">
        <!-- Lado izquierdo con imagen y parallax -->
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

        <!-- Lado derecho con formulario -->
        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h2>Bienvenido</h2>
                    <p>Inicia sesión para gestionar tus reservas</p>
                </div>

                <!-- Formulario tradicional -->
                <form action="/Sistema_RentACar/index.php?controller=Auth&action=login" method="POST">
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
                        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="submit-btn">Continuar</button>
                </form>

                <div class="divider">
                    <span class="divider-line"></span>
                    <span>O continúa con</span>
                    <span class="divider-line"></span>
                </div>

                <!-- Botón de Google -->
                <a href="/Sistema_RentACar/index.php?controller=GoogleAuth&action=login" class="google-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 12-4.53z" fill="#EA4335"></path>
                    </svg>
                    <span>Continuar con Google</span>
                </a>

                <div class="legal">
                    Al continuar, aceptas nuestros <a href="#">Términos</a> y <a href="#">Política de Privacidad</a>.
                </div>

                <div class="register-link">
                    ¿No tienes cuenta? <a href="#">Regístrate</a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="page-footer">
    <div>© 2025 GoCar Rent A Car. Todos los derechos reservados.</div>
    <div class="footer-links">
        <a href="#">Privacidad</a>
        <a href="#">Términos</a>
        <a href="#">Cookies</a>
    </div>
</footer>

<!-- JavaScript para efectos y animaciones -->
<script>
    // Toggle contraseña
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = event.currentTarget.querySelector('.material-symbols-outlined');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Efecto parallax en la imagen de fondo
    window.addEventListener('scroll', () => {
        const heroImage = document.getElementById('heroImage');
        if (heroImage) {
            const scrollY = window.scrollY;
            heroImage.style.transform = `translateY(${scrollY * 0.1}px) scale(1.1)`;
        }
    });

    // Partículas (opcional - para un efecto más dinámico)
    const canvas = document.getElementById('particles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];

        function initParticles() {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
            particles = [];
            for (let i = 0; i < 50; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    size: Math.random() * 2 + 1,
                    speedY: Math.random() * 1 + 0.5,
                    opacity: Math.random() * 0.5 + 0.2
                });
            }
        }

        function drawParticles() {
            ctx.clearRect(0, 0, width, height);
            ctx.fillStyle = '#137fec';
            particles.forEach(p => {
                ctx.globalAlpha = p.opacity;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(updateParticles);
        }

        function updateParticles() {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(p => {
                p.y -= p.speedY;
                if (p.y < 0) {
                    p.y = height;
                }
                ctx.globalAlpha = p.opacity;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = '#137fec';
                ctx.fill();
            });
            requestAnimationFrame(updateParticles);
        }

        window.addEventListener('resize', () => {
            initParticles();
        });

        initParticles();
        updateParticles();
    }
</script>

</body>
</html>