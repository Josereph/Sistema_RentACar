<?php
$usuario = $_SESSION['admin_nombre'] ?? 'Empleado';
$seccion = $seccion ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #101922; border-bottom: 2px solid #137fec;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?= url('index.php?area=empleado&controller=DashboardEmpleado&action=index') ?>">
            <img src="<?= url('assets/img/img.jpeg') ?>" alt="GoCar" height="40" class="me-2">
            <span class="fw-bold" style="color: #ffffff;">GoCar</span>
            <span class="ms-1 small" style="color: #137fec;">Empleado</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEmpleado" style="border-color: #137fec;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarEmpleado">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'dashboard' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=DashboardEmpleado&action=index') ?>">
                        <i class="fas fa-tachometer-alt" style="color: #137fec;"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'clientes' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=index') ?>">
                        <i class="fas fa-users" style="color: #137fec;"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'vehiculos' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=VehiculosEmpleado&action=index') ?>">
                        <i class="fas fa-car" style="color: #137fec;"></i> Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'reservas' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=index') ?>">
                        <i class="fas fa-calendar-check" style="color: #137fec;"></i> Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'contratos' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=ContratosEmpleado&action=index') ?>">
                        <i class="fas fa-file-contract" style="color: #137fec;"></i> Contratos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'devoluciones' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=DevolucionEmpleado&action=listado') ?>">
                        <i class="fas fa-undo-alt" style="color: #137fec;"></i> Devoluciones
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" style="color: #ffffff;">
                        <i class="fas fa-user-circle me-2" style="color: #137fec; font-size: 1.5rem;"></i>
                        <span><?= htmlspecialchars($usuario) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" style="background-color: #101922; border: 1px solid #137fec;">
                        <li><a class="dropdown-item" href="<?= url('index.php?area=empleado&controller=PerfilEmpleado&action=index') ?>"><i class="fas fa-id-card me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider" style="border-color: #137fec;"></li>
                        <li><a class="dropdown-item text-danger" href="<?= url('index.php?area=empleado&controller=Auth&action=logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 70px;"></div>