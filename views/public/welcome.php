<!DOCTYPE html>
<html class="dark" lang="es">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>GoCar | Bienvenido</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#137fec",
            "background-light": "#f6f7f8",
            "background-dark": "#101922",
          },
          fontFamily: {
            "display": ["Manrope", "sans-serif"]
          },
        },
      },
    }
  </script>
  <style>
    .car-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .car-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white transition-colors duration-300">

  <!-- Navbar simple: logo + botones de acceso -->
  <header class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center gap-2 text-primary">
          <span class="material-symbols-outlined text-3xl">directions_car</span>
          <h1 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">GoCar</h1>
        </div>

        <!-- Botones de autenticación -->
        <div class="flex items-center gap-3">
          <a href="/Sistema_RentACar/views/admin/auth/login.php" 
             class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary px-4 py-2 transition-colors">
            Iniciar sesión
          </a>
          <a href="/Sistema_RentACar/views/admin/auth/registro.php" 
             class="bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2 rounded-lg transition-all shadow-lg shadow-primary/20">
            Registrarse
          </a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <!-- Hero Section -->
    <section class="relative py-16 lg:py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider rounded-full mb-6">
            Alquiler de vehículos premium
          </span>
          <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white mb-6">
            Conduce el <span class="text-primary">coche de tus sueños</span> hoy
          </h1>
          <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 max-w-lg">
            Más de 120 vehículos disponibles. Reserva online y recoge en cualquiera de nuestras 45 ubicaciones. Sin filas, sin papeleo.
          </p>
          <div class="flex flex-wrap gap-4">
            <a href="/Sistema_RentACar/views/admin/auth/login.php" 
               class="bg-primary text-white font-bold px-8 py-4 rounded-lg hover:shadow-xl hover:shadow-primary/30 transition-all">
              Comenzar ahora
            </a>
            <a href="#destacados" 
               class="bg-transparent border-2 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold px-8 py-4 rounded-lg hover:border-primary hover:text-primary transition-all">
              Ver flota
            </a>
          </div>
        </div>
        <div class="relative hidden lg:block">
          <div class="rounded-2xl overflow-hidden shadow-2xl h-[500px]">
            <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                 alt="Coche de lujo" class="w-full h-full object-cover">
          </div>
          <div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-lg flex items-center gap-4">
            <div class="bg-green-100 dark:bg-green-900/30 text-green-600 p-2 rounded-full">
              <span class="material-symbols-outlined">verified</span>
            </div>
            <div>
              <p class="text-sm font-bold">+5.000 viajeros</p>
              <p class="text-xs text-slate-500">confían en nosotros</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Estadísticas -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-12">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
          <p class="text-3xl font-bold text-primary">120+</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Vehículos</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
          <p class="text-3xl font-bold text-primary">15+</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Años de experiencia</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
          <p class="text-3xl font-bold text-primary">45+</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Ubicaciones</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 text-center">
          <p class="text-3xl font-bold text-primary">24/7</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Soporte</p>
        </div>
      </div>
    </section>

    <!-- Categorías de vehículos -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-16">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Nuestras categorías</h2>
        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Elige entre una amplia variedad de vehículos para cada ocasión</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- SUV -->
        <div class="group relative rounded-xl overflow-hidden aspect-[4/3] cursor-pointer">
          <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
               alt="SUV" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
            <h3 class="text-white text-xl font-bold">SUV</h3>
            <p class="text-white/80 text-sm">Espacio y confort para toda la familia</p>
          </div>
        </div>
        <!-- Sedán -->
        <div class="group relative rounded-xl overflow-hidden aspect-[4/3] cursor-pointer">
          <img src="https://images.unsplash.com/photo-1550355291-bbee04a92027?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
               alt="Sedán" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
            <h3 class="text-white text-xl font-bold">Sedán</h3>
            <p class="text-white/80 text-sm">Elegancia y eficiencia para la ciudad</p>
          </div>
        </div>
        <!-- Deportivo -->
        <div class="group relative rounded-xl overflow-hidden aspect-[4/3] cursor-pointer">
          <img src="https://images.unsplash.com/photo-1580273916550-e323be2ae537?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
               alt="Deportivo" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
            <h3 class="text-white text-xl font-bold">Deportivo</h3>
            <p class="text-white/80 text-sm">Potencia y adrenalina al volante</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Vehículos destacados (estáticos) -->
    <section id="destacados" class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-16 bg-white dark:bg-slate-900/50 rounded-3xl">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Vehículos destacados</h2>
        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Los favoritos de nuestros clientes</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- BMW X5 -->
        <div class="car-card bg-white dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
          <div class="h-48 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 alt="BMW X5" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">BMW X5</h3>
            <p class="text-sm text-slate-500 mb-4">SUV de lujo · 5 asientos</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-black text-primary">$120</span>
              <span class="text-xs text-slate-500">/día</span>
            </div>
            <div class="mt-4">
              <a href="/Sistema_RentACar/views/admin/auth/login.php" 
                 class="block text-center bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg transition-all">
                Reservar
              </a>
            </div>
          </div>
        </div>
        <!-- Audi A6 -->
        <div class="car-card bg-white dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
          <div class="h-48 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1550355291-bbee04a92027?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 alt="Audi A6" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Audi A6</h3>
            <p class="text-sm text-slate-500 mb-4">Sedán ejecutivo · 5 asientos</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-black text-primary">$95</span>
              <span class="text-xs text-slate-500">/día</span>
            </div>
            <div class="mt-4">
              <a href="/Sistema_RentACar/views/admin/auth/login.php" 
                 class="block text-center bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg transition-all">
                Reservar
              </a>
            </div>
          </div>
        </div>
        <!-- Porsche 911 -->
        <div class="car-card bg-white dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
          <div class="h-48 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1580273916550-e323be2ae537?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 alt="Porsche 911" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Porsche 911</h3>
            <p class="text-sm text-slate-500 mb-4">Deportivo · 2 asientos</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-black text-primary">$250</span>
              <span class="text-xs text-slate-500">/día</span>
            </div>
            <div class="mt-4">
              <a href="/Sistema_RentACar/views/admin/auth/login.php" 
                 class="block text-center bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg transition-all">
                Reservar
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-12">
        <a href="/Sistema_RentACar/views/admin/auth/login.php" 
           class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
          Ver toda la flota
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
      </div>
    </section>

    <!-- Por qué elegirnos -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-16">
      <div class="grid md:grid-cols-2 gap-8">
        <div class="flex gap-4">
          <span class="material-symbols-outlined text-4xl text-primary">verified_user</span>
          <div>
            <h3 class="text-xl font-bold mb-2">Seguro a todo riesgo</h3>
            <p class="text-slate-600 dark:text-slate-400">Todos nuestros alquileres incluyen cobertura completa sin franquicia.</p>
          </div>
        </div>
        <div class="flex gap-4">
          <span class="material-symbols-outlined text-4xl text-primary">support_agent</span>
          <div>
            <h3 class="text-xl font-bold mb-2">Asistencia 24/7</h3>
            <p class="text-slate-600 dark:text-slate-400">Estamos disponibles a cualquier hora para ayudarte en carretera.</p>
          </div>
        </div>
        <div class="flex gap-4">
          <span class="material-symbols-outlined text-4xl text-primary">sell</span>
          <div>
            <h3 class="text-xl font-bold mb-2">Mejor precio garantizado</h3>
            <p class="text-slate-600 dark:text-slate-400">Si encuentras una oferta mejor, te igualamos el precio y te damos un 10% de descuento.</p>
          </div>
        </div>
        <div class="flex gap-4">
          <span class="material-symbols-outlined text-4xl text-primary">lock_reset</span>
          <div>
            <h3 class="text-xl font-bold mb-2">Cancelación flexible</h3>
            <p class="text-slate-600 dark:text-slate-400">Cancela gratis hasta 24 horas antes de la recogida.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Llamada a la acción final -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto pb-20">
      <div class="bg-primary rounded-2xl p-12 text-center">
        <h2 class="text-3xl font-extrabold text-white mb-4">¿Listo para conducir?</h2>
        <p class="text-white/80 text-lg mb-8 max-w-xl mx-auto">Regístrate en menos de 2 minutos y accede a nuestra flota completa.</p>
        <a href="/Sistema_RentACar/views/admin/auth/registro.php" 
           class="inline-block bg-white text-primary font-bold px-8 py-4 rounded-lg hover:bg-slate-100 transition-all shadow-lg">
          Crear cuenta gratis
        </a>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-white dark:bg-background-dark border-t border-slate-200 dark:border-slate-800 px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <div class="flex items-center gap-2 text-primary mb-4">
          <span class="material-symbols-outlined text-2xl">directions_car</span>
          <span class="text-lg font-bold text-slate-900 dark:text-white">GoCar</span>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400">La mejor experiencia en alquiler de vehículos desde 2009.</p>
      </div>
      <div>
        <h4 class="font-bold text-slate-900 dark:text-white mb-4">Compañía</h4>
        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
          <li><a href="#" class="hover:text-primary">Sobre nosotros</a></li>
          <li><a href="#" class="hover:text-primary">Carreras</a></li>
          <li><a href="#" class="hover:text-primary">Prensa</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold text-slate-900 dark:text-white mb-4">Soporte</h4>
        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
          <li><a href="#" class="hover:text-primary">Centro de ayuda</a></li>
          <li><a href="#" class="hover:text-primary">Política de privacidad</a></li>
          <li><a href="#" class="hover:text-primary">Términos de servicio</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold text-slate-900 dark:text-white mb-4">Contacto</h4>
        <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
          <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">mail</span> hola@gocar.com</li>
          <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">phone</span> +1 (555) 000-0000</li>
        </ul>
      </div>
    </div>
    <div class="max-w-7xl mx-auto mt-8 pt-8 border-t border-slate-200 dark:border-slate-800 text-center text-sm text-slate-500">
      © <?= date('Y') ?> GoCar Rent A Car. Todos los derechos reservados.
    </div>
  </footer>
</body>
</html>