<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= url('index.php?v=home') ?>">
      <i class="fas fa-car" style="color:#137fec;"></i> GO CAR
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <?php $v = $_GET['v'] ?? ''; ?>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($v === 'home') ? 'active' : '' ?>" href="<?= url('index.php?v=home') ?>">
            Inicio
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($v === 'catalogo') ? 'active' : '' ?>" href="<?= url('index.php?v=catalogo') ?>">
            Catálogo
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($v === 'reservas') ? 'active' : '' ?>" href="<?= url('index.php?v=reservas') ?>">
            Reservas
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= url('index.php?v=home#sobre-nosotros') ?>">
            Sobre Nosotros
          </a>
        </li>

        <!-- ADMIN (MVC controller/action) -->
        <li class="nav-item">
          <a class="nav-link" href="<?= url('index.php?controller=Administracion&action=index') ?>">
            <i class="fas fa-user-shield"></i> Admin
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>