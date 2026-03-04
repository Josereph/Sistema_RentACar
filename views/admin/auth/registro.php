<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | GoCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#137fec",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        display: ["Manrope", "sans-serif"],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-background-dark font-display text-white">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-[#1c2a38] p-8 rounded-xl border border-[#233648] max-w-md w-full">
            <h1 class="text-2xl font-bold mb-6 text-center">Crear cuenta</h1>
            
            <form method="POST" action="/Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=registrar" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Nombre</label>
                    <input type="text" name="nombre" required 
                           class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Apellido</label>
                    <input type="text" name="apellido" required 
                           class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Correo electrónico</label>
                    <input type="email" name="correo" required 
                           class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Contraseña</label>
                    <input type="password" name="password" required 
                           class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Confirmar contraseña</label>
                    <input type="password" name="confirm_password" required 
                           class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 px-4 focus:ring-1 focus:ring-primary">
                </div>

                <button type="submit" 
                        class="w-full bg-primary hover:bg-primary/90 py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/20 transition-all mt-6">
                    Registrarse
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-[#92adc9]">
                ¿Ya tienes cuenta? 
                <a href="/Sistema_RentACar/views/admin/auth/login.php" class="text-primary hover:underline">Inicia sesión</a>
            </div>
        </div>
    </div>
</body>
</html>