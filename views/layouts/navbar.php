<?php
// views/layouts/navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('url')) {
    function url($path = '') {
        $base = '/Sistema_RentACar';
        return $base . '/' . ltrim($path, '/');
    }
}

$current = basename($_SERVER['SCRIPT_NAME']);
if (!function_exists('active')) {
    function active($file, $current) {
        return $file === $current ? 'is-active' : '';
    }
}

// Verificar perfil incompleto si el cliente está logueado
$perfilIncompleto = false;
if (isset($_SESSION['cliente_id'])) {
    require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ClienteModel.php';
    $clienteModel = new ClienteModel();
    $perfilIncompleto = !$clienteModel->perfilCompleto($_SESSION['cliente_id']);
}

// inicial para el avatar
$initial = 'G';
if (!empty($_SESSION['cliente_nombre'])) {
    $initial = strtoupper(mb_substr($_SESSION['cliente_nombre'], 0, 1, 'UTF-8'));
}
?>

<!-- Material Symbols (lo cargamos aquí para que no dependas del layout) -->
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap">

<style>
/* =========================
   NAVBAR GO CAR - EMBEBIDO
   ========================= */
:root{
  --nav-bg: #101922;
  --nav-border: #233648;
  --nav-text: #92adc9;
  --nav-white: #ffffff;
  --primary: #3b82f6;      /* cámbialo si quieres */
  --danger: #fb7185;
  --warn: #fbbf24;
  --card: rgba(255,255,255,.06);
  --card2: rgba(255,255,255,.08);
  --shadow: rgba(59,130,246,.22);
}

/* wrapper general */
.gocar-navbar{
  position: sticky;
  top: 0;
  z-index: 999;
  background: var(--nav-bg);
  border-bottom: 1px solid var(--nav-border);
  padding: 12px 16px;
  box-sizing: border-box;
}

/* contenedor */
.gocar-navbar__wrap{
  max-width: 1150px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

/* marca */
.gocar-brand{
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
}
.gocar-brand__logo{
  width: 34px;
  height: 34px;
  color: var(--primary);
  display:flex;
}
.gocar-brand__img{
  width: 34px;
  height: 34px;
  border-radius: 6px;
  object-fit: cover;
  display: block;
}
.gocar-brand__text{
  line-height: 1.05;
}
.gocar-brand__title{
  display:block;
  color: var(--nav-white);
  font-weight: 900;
  letter-spacing: .3px;
  font-size: 16px;
}
.gocar-brand__sub{
  display:block;
  color: var(--nav-text);
  font-size: 11px;
  margin-top: 2px;
}

/* links escritorio */
.gocar-links{
  display: flex;
  align-items: center;
  gap: 6px;
}
.gocar-link{
  color: var(--nav-text);
  text-decoration: none;
  font-weight: 700;
  font-size: 14px;
  padding: 9px 12px;
  border-radius: 12px;
  transition: 160ms ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.gocar-link:hover{
  color: var(--nav-white);
  background: var(--card);
}
.gocar-link.is-active{
  color: var(--nav-white);
  background: rgba(59,130,246,.18);
  box-shadow: 0 12px 30px rgba(0,0,0,.18) inset;
}

/* derecha */
.gocar-right{
  display: flex;
  align-items: center;
  gap: 10px;
}

/* botón principal */
.gocar-btn{
  background: var(--primary);
  color: var(--nav-white);
  font-weight: 900;
  font-size: 13px;
  padding: 10px 14px;
  border-radius: 12px;
  text-decoration: none;
  transition: 160ms ease;
  box-shadow: 0 12px 30px var(--shadow);
  display: inline-flex;
  align-items:center;
  gap: 8px;
}
.gocar-btn:hover{
  filter: brightness(.95);
  transform: translateY(-1px);
}

/* avatar */
.gocar-avatar{
  width: 40px;
  height: 40px;
  border-radius: 999px;
  background: rgba(59,130,246,.18);
  color: var(--nav-white);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 900;
  text-decoration: none;
  position: relative;
  transition: 160ms ease;
}
.gocar-avatar:hover{
  background: rgba(59,130,246,.26);
}
.gocar-badge{
  position: absolute;
  top: -2px;
  right: -2px;
  width: 11px;
  height: 11px;
  border-radius: 999px;
  background: var(--warn);
  border: 2px solid var(--nav-bg);
}

/* logout icon link */
.gocar-iconlink{
  color: var(--nav-text);
  text-decoration: none;
  padding: 8px;
  border-radius: 12px;
  transition: 160ms ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.gocar-iconlink:hover{
  color: var(--nav-white);
  background: var(--card);
}
.material-symbols-outlined{
  font-size: 22px;
  line-height: 1;
}

/* botón menú móvil */
.gocar-mobilebtn{
  display: none;
  background: transparent;
  border: 0;
  color: var(--nav-white);
  padding: 8px;
  border-radius: 12px;
  cursor: pointer;
  transition: 160ms ease;
}
.gocar-mobilebtn:hover{
  background: var(--card);
}

/* panel móvil */
.gocar-mobilepanel{
  max-width: 1150px;
  margin: 10px auto 0;
  padding: 0 8px 12px;
  display: none;
}
.gocar-mobilepanel.show{
  display: block;
}
.gocar-mobileitem{
  display:block;
  padding: 12px 12px;
  margin-top: 8px;
  border-radius: 14px;
  color: var(--nav-text);
  text-decoration: none;
  font-weight: 800;
  background: var(--card);
  transition: 160ms ease;
}
.gocar-mobileitem:hover{
  background: var(--card2);
  color: var(--nav-white);
}
.gocar-mobileitem.primary{
  background: rgba(59,130,246,.20);
  color: var(--nav-white);
}
.gocar-mobileitem.primary:hover{
  background: rgba(59,130,246,.28);
}
.gocar-mobileitem.danger{
  background: rgba(251,113,133,.12);
  color: #fecdd3;
}
.gocar-mobileitem.danger:hover{
  background: rgba(251,113,133,.20);
  color: #fff;
}

/* responsive */
@media (max-width: 1024px){
  .gocar-links{ display:none; }
  .gocar-mobilebtn{ display:inline-flex; }
  .gocar-btn.desktop-only{ display:none; }
}
</style>

<header class="gocar-navbar" data-navbar>
  <div class="gocar-navbar__wrap">

    <!-- Logo y marca -->
    <a class="gocar-brand" href="<?= url('views/ReservaCatalogo/views/home.php') ?>">
      <span class="gocar-brand__logo" aria-hidden="true">
        <img src="<?= url('assets/img/img.jpeg') ?>" alt="GO CAR" class="gocar-brand__img">
      </span>
      <span class="gocar-brand__text">
        <span class="gocar-brand__title">GO CAR</span>
        <span class="gocar-brand__sub">Rent a Car</span>
      </span>
    </a>

    <!-- Navegación escritorio (solo visible si cliente logueado) -->
    <?php if (isset($_SESSION['cliente_id'])): ?>
      <nav class="gocar-links" aria-label="Navegación principal">
        <a class="gocar-link <?= active('home.php', $current) ?>" href="<?= url('views/ReservaCatalogo/views/home.php') ?>">
          Inicio
        </a>
        <a class="gocar-link <?= active('catalogo.php', $current) ?>" href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">
          Flota
        </a>
        <a class="gocar-link <?= active('mis_reservas.php', $current) ?>" href="<?= url('views/ReservaCatalogo/views/mis_reservas.php') ?>">
          Mis reservas
        </a>
      </nav>
    <?php endif; ?>

    <!-- Acciones derecha -->
    <div class="gocar-right">

      <?php if (isset($_SESSION['cliente_id'])): ?>

        <a class="gocar-btn desktop-only" href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">
          <span class="material-symbols-outlined" aria-hidden="true">directions_car</span>
          Reservar ahora
        </a>

        <!-- Avatar / Perfil -->
        <a class="gocar-avatar" href="<?= url('index.php?area=cliente&controller=Cliente&action=perfil') ?>" title="Mi perfil">
          <?= htmlspecialchars($initial) ?>
          <?php if ($perfilIncompleto): ?>
            <span class="gocar-badge" title="Perfil incompleto"></span>
          <?php endif; ?>
        </a>

        <!-- Logout -->
        <a class="gocar-iconlink desktop-only" href="<?= url('index.php?area=cliente&controller=Cliente&action=logout') ?>" title="Cerrar sesión">
          <span class="material-symbols-outlined" aria-hidden="true">logout</span>
        </a>

      <?php else: ?>

        <a class="gocar-btn" href="<?= url('views/admin/auth/login.php') ?>">
          <span class="material-symbols-outlined" aria-hidden="true">login</span>
          Iniciar Sesión
        </a>

      <?php endif; ?>

      <!-- Botón menú móvil -->
      <button class="gocar-mobilebtn" type="button" aria-expanded="false" aria-controls="gocarMobilePanel" data-mobile-toggle>
        <span class="material-symbols-outlined" aria-hidden="true">menu</span>
      </button>
    </div>
  </div>

  <!-- Menú móvil (solo si logueado) -->
  <?php if (isset($_SESSION['cliente_id'])): ?>
    <div id="gocarMobilePanel" class="gocar-mobilepanel" data-mobile-panel>
      <a class="gocar-mobileitem" href="<?= url('views/ReservaCatalogo/views/home.php') ?>">Inicio</a>
      <a class="gocar-mobileitem" href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">Flota</a>
      <a class="gocar-mobileitem" href="<?= url('views/ReservaCatalogo/views/mis_reservas.php') ?>">Mis reservas</a>

      <a class="gocar-mobileitem primary" href="<?= url('views/ReservaCatalogo/views/catalogo.php') ?>">Reservar ahora</a>
      <a class="gocar-mobileitem" href="<?= url('index.php?area=cliente&controller=Cliente&action=perfil') ?>">Mi perfil</a>
      <a class="gocar-mobileitem danger" href="<?= url('index.php?area=cliente&controller=Cliente&action=logout') ?>">Cerrar sesión</a>
    </div>
  <?php endif; ?>
</header>

<script>
(function(){
  // Toggle menú móvil (sin depender de DOMContentLoaded, por si lo incluyen a mitad)
  const toggle = document.querySelector('[data-mobile-toggle]');
  const panel  = document.querySelector('[data-mobile-panel]');

  if (!toggle || !panel) return;

  const setExpanded = (val) => toggle.setAttribute('aria-expanded', val ? 'true' : 'false');

  toggle.addEventListener('click', function(){
    panel.classList.toggle('show');
    setExpanded(panel.classList.contains('show'));
  });

  // Cerrar al hacer click fuera (opcional, se siente más pro)
  document.addEventListener('click', function(e){
    const navbar = document.querySelector('[data-navbar]');
    if (!navbar) return;
    if (!navbar.contains(e.target) && panel.classList.contains('show')) {
      panel.classList.remove('show');
      setExpanded(false);
    }
  });
})();
</script>