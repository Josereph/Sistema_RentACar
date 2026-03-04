<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= url('index.php?area=cliente&controller=Catalogo&action=index') ?>">
            <i class="fas fa-car me-2"></i>GoCar
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCliente">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCliente">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('index.php?area=cliente&controller=Catalogo&action=index') ?>">
                        <i class="fas fa-search me-1"></i>Catálogo
                    </a>
                </li>
                <?php if (isset($_SESSION['cliente_logged'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('index.php?area=cliente&controller=Reserva&action=misReservas') ?>">
                        <i class="fas fa-calendar-check me-1"></i>Mis Reservas
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['cliente_logged'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($_SESSION['cliente_nombre']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= url('index.php?area=cliente&controller=Perfil&action=index') ?>"><i class="fas fa-id-card me-2"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= url('index.php?area=cliente&controller=Auth&action=logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('index.php?area=cliente&controller=Auth&action=login') ?>">
                        <i class="fas fa-sign-in-alt me-1"></i>Iniciar sesión
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('index.php?area=cliente&controller=Auth&action=registro') ?>">
                        <i class="fas fa-user-plus me-1"></i>Registrarse
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>