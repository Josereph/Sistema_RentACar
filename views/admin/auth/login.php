<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | CarWash RentaCar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0b1a2e, #1a2f3f);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            background: rgba(18, 28, 40, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(46, 196, 182, 0.2);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .login-card h2 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-card .subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-control {
            background: rgba(0,0,0,0.3);
            border: 1px solid #2a3a4a;
            border-radius: 12px;
            color: #fff;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            background: rgba(0,0,0,0.5);
            border-color: #2ec4b6;
            box-shadow: 0 0 0 3px rgba(46,196,182,0.25);
            color: #fff;
        }
        .btn-login {
            background: #2ec4b6;
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            color: #0b1a2e;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: #3dd1c3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px #2ec4b6;
        }
        .alert {
            border-radius: 12px;
            background: rgba(230,57,70,0.2);
            border: 1px solid #e63946;
            color: #ffb3b3;
        }
        .logo-icon {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-icon">🚗</div>
        <h2 class="text-center">CarWash RentaCar</h2>
        <p class="subtitle text-center">Panel de Administración</p>

        <?php if ($error): ?>
            <div class="alert mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=Auth&action=login">
            <div class="mb-3">
                <label class="form-label text-muted">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="admin@carwash.com" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">© 2024 CarWash RentaCar</small>
        </div>
    </div>
</body>
</html>