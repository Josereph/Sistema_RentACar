<?php
$usuario = $_SESSION['admin_nombre'] ?? 'Empleado';
$seccion = $seccion ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url('index.php?area=empleado&controller=DashboardEmpleado&action=index') ?>">
            <i class="fas fa-user-tie me-2" style="color: #137fec;"></i>GoCar <small>Empleado</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEmpleado">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarEmpleado">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'dashboard' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=DashboardEmpleado&action=index') ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'clientes' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=ClientesEmpleado&action=index') ?>">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'vehiculos' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=VehiculosEmpleado&action=index') ?>">
                        <i class="fas fa-car"></i> Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'reservas' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=ReservasEmpleado&action=index') ?>">
                        <i class="fas fa-calendar-check"></i> Reservas
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link <?= $seccion == 'contratos' ? 'active' : '' ?>" 
       href="<?= url('index.php?area=empleado&controller=ContratosEmpleado&action=index') ?>">
        <i class="fas fa-file-contract"></i> Contratos
    </a>
</li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'devoluciones' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=empleado&controller=DevolucionEmpleado&action=listado') ?>">
                        <i class="fas fa-undo-alt"></i> Devoluciones
                    </a>
                </li>
               
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($usuario) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= url('index.php?area=empleado&controller=PerfilEmpleado&action=index') ?>"><i class="fas fa-id-card me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= url('index.php?controller=Auth&action=logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 70px;"></div>