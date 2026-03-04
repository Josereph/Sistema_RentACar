<?php
// Fallback por si alguien abre el archivo directo sin pasar por index.php
if (!function_exists('url')) {
  $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  $base = preg_replace('#/views/ReservaCatalogo/views$#', '', $base);
  if ($base === '') $base = '/Sistema_RentACar';
  define('BASE_URL', $base);
  function url($path = '') { return BASE_URL . '/' . ltrim($path, '/'); }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GO CAR | Tu plataforma de alquiler de vehículos</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet">

  <!-- BASE GLOBAL -->
  <link rel="stylesheet" href="<?= url('assets/css/client.css') ?>">

  <!-- TU DISEÑO (ReservaCatalogo) -->
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/home.css') ?>">
</head>

<body class="gocar-body">

  <?php include __DIR__ . '/../../layouts/navbar.php'; ?>

  <!-- HERO -->
  <header class="gocar-hero">
    <div class="gocar-hero__overlay"></div>

    <div class="container position-relative gocar-hero__content">
      <div class="gocar-hero__card">
        <h1 class="gocar-hero__title">
          Conduce el futuro de la movilidad
        </h1>

        <p class="gocar-hero__subtitle">
          Nuestra misión es brindar rentas de vehículos confiables, modernas y accesibles para impulsar tu viaje,
          donde sea que te lleve.
        </p>

        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
          <a class="btn gocar-btn gocar-btn--primary" href="<?= url('index.php?v=catalogo') ?>">
            Explorar Flota
          </a>
          <a class="btn gocar-btn gocar-btn--outline" href="#journey">
            Nuestra Historia
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- STATS -->
  <section class="gocar-section gocar-section--tight">
    <div class="container">
      <div class="row g-3 justify-content-center">
        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-stat">
            <div class="gocar-stat__label">Autos Rentados</div>
            <div class="gocar-stat__value">50k+</div>
            <div class="gocar-stat__bar"></div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-stat">
            <div class="gocar-stat__label">Clientes Felices</div>
            <div class="gocar-stat__value">120k+</div>
            <div class="gocar-stat__bar"></div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-stat">
            <div class="gocar-stat__label">Años Activos</div>
            <div class="gocar-stat__value">15+</div>
            <div class="gocar-stat__bar"></div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-stat">
            <div class="gocar-stat__label">Puntos de Atención</div>
            <div class="gocar-stat__value">45+</div>
            <div class="gocar-stat__bar"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- VALUES -->
  <section class="gocar-section">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="gocar-h2">Nuestros Valores</h2>
        <p class="gocar-muted">
          Nuestra base es la confianza, la excelencia y el compromiso con una experiencia de renta impecable.
        </p>
      </div>

      <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-card">
            <div class="gocar-card__icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h3 class="gocar-card__title">Confiabilidad</h3>
            <p class="gocar-card__text">
              Mantenemos la flota en óptimas condiciones para que cada viaje sea seguro y predecible.
            </p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-card">
            <div class="gocar-card__icon"><i class="fa-solid fa-lightbulb"></i></div>
            <h3 class="gocar-card__title">Innovación</h3>
            <p class="gocar-card__text">
              Desde reservas rápidas hasta mejoras continuas, usamos tecnología para darte más control.
            </p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-card">
            <div class="gocar-card__icon"><i class="fa-solid fa-users"></i></div>
            <h3 class="gocar-card__title">Cliente Primero</h3>
            <p class="gocar-card__text">
              Tu satisfacción es la meta: procesos claros, atención rápida y soluciones reales.
            </p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="gocar-card">
            <div class="gocar-card__icon"><i class="fa-solid fa-eye"></i></div>
            <h3 class="gocar-card__title">Transparencia</h3>
            <p class="gocar-card__text">
              Precios y condiciones sin letras pequeñas. Te decimos todo, desde el inicio.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- JOURNEY -->
  <section id="journey" class="gocar-section">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-12 col-lg-6">
          <div class="gocar-media">
            <img
              src="https://images.unsplash.com/photo-1511919884226-fd3cad34687c?q=80&w=1600&auto=format&fit=crop"
              alt="Vehículo clásico"
              class="img-fluid"
            />
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <h2 class="gocar-h2 text-start mb-3">Nuestro Camino</h2>

          <div class="gocar-timeline">
            <div class="gocar-timeline__item">
              <span class="gocar-pill">2009</span>
              <div>
                <h4 class="gocar-timeline__title">Fundación</h4>
                <p class="gocar-timeline__text">
                  Nacimos con una visión: rentas simples, rápidas y confiables para cualquier necesidad.
                </p>
              </div>
            </div>

            <div class="gocar-timeline__item">
              <span class="gocar-pill">2015</span>
              <div>
                <h4 class="gocar-timeline__title">Expansión</h4>
                <p class="gocar-timeline__text">
                  Sumamos más vehículos, categorías y puntos de atención para cubrir más rutas.
                </p>
              </div>
            </div>

            <div class="gocar-timeline__item">
              <span class="gocar-pill">2024</span>
              <div>
                <h4 class="gocar-timeline__title">Evolución Digital</h4>
                <p class="gocar-timeline__text">
                  Lanzamos una experiencia web moderna para buscar, filtrar y reservar en minutos.
                </p>
              </div>
            </div>
          </div>

          <div class="mt-4 d-flex gap-3 flex-wrap">
            <a class="btn gocar-btn gocar-btn--primary" href="<?= url('index.php?v=catalogo') ?>">
              Ver Catálogo
            </a>
            <a class="btn gocar-btn gocar-btn--outline" href="<?= url('index.php?v=reservas') ?>">
              Ir a Reservas
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ADVANTAGE -->
  <section class="gocar-section">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="gocar-h2">La Ventaja GO CAR</h2>
      </div>

      <div class="row g-3">
        <div class="col-12 col-lg-6">
          <div class="gocar-adv">
            <div class="gocar-adv__icon"><i class="fa-solid fa-shield-heart"></i></div>
            <div>
              <h3 class="gocar-adv__title">Seguro Integral</h3>
              <p class="gocar-adv__text">Conduce con tranquilidad con cobertura clara y opciones flexibles.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="gocar-adv">
            <div class="gocar-adv__icon"><i class="fa-solid fa-life-ring"></i></div>
            <div>
              <h3 class="gocar-adv__title">Asistencia 24/7</h3>
              <p class="gocar-adv__text">Si algo pasa en ruta, te apoyamos para que sigas tu viaje.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="gocar-adv">
            <div class="gocar-adv__icon"><i class="fa-solid fa-road"></i></div>
            <div>
              <h3 class="gocar-adv__title">Kilometraje Flexible</h3>
              <p class="gocar-adv__text">Explora sin estrés: opciones para viajes cortos o largos.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="gocar-adv">
            <div class="gocar-adv__icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
              <h3 class="gocar-adv__title">Cancelación Flexible</h3>
              <p class="gocar-adv__text">Planes cambian: ajusta tu reserva con reglas sencillas.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="gocar-section">
    <div class="container">
      <div class="gocar-cta">
        <h2 class="gocar-cta__title">¿Listo para salir a la carretera?</h2>
        <p class="gocar-cta__text">
          Reserva tu vehículo en menos de un minuto y disfruta una experiencia de renta moderna.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">
          <a class="btn gocar-btn gocar-btn--light" href="<?= url('index.php?v=catalogo') ?>">Reservar Ahora</a>
          <a class="btn gocar-btn gocar-btn--outlineLight" href="#contact">Contactar Soporte</a>
        </div>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/../../layouts/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= url('assets/js/client-ui.js') ?>"></script>
</body>
</html>