<?php
$usuario = $_SESSION['admin_nombre'] ?? 'Admin';
$inicial = strtoupper(substr($usuario, 0, 1));
$seccion = $seccion ?? '';
$rol = $_SESSION['admin_rol'] ?? 'operador';
?>
<nav class="navbar navbar-expand-xl navbar-dark fixed-top" style="background-color: #101922; border-bottom: 2px solid #137fec;">
    <div class="container-fluid">
        <!-- Logo y nombre de la empresa -->
        <a class="navbar-brand d-flex align-items-center" href="<?= url('index.php?area=admin&controller=Dashboard&action=index') ?>">
            <img src="<?= url('assets/img/img.jpeg') ?>" alt="GoCar" height="40" class="me-2">
            <span class="fw-bold" style="color: #ffffff;">GoCar</span>
            <span class="ms-1 small" style="color: #137fec;">Rent A Car</span>
        </a>

        <!-- Botón para colapsar en móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" style="border-color: #137fec;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'dashboard' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=admin&controller=Dashboard&action=index') ?>">
                        <i class="fas fa-tachometer-alt" style="color: #137fec;"></i> Dashboard
                    </a>
                </li>

                <!-- Menú Gestión -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($seccion, ['clientes', 'vehiculos', 'reservas', 'contratos']) ? 'active' : '' ?>" 
                       href="#" id="gestionDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-edit" style="color: #137fec;"></i> Gestión
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #101922; border: 1px solid #137fec;">
                        <li><a class="dropdown-item <?= $seccion == 'clientes' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Clientes&action=index') ?>"><i class="fas fa-users me-2"></i>Clientes</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'vehiculos' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Vehiculos&action=index') ?>"><i class="fas fa-car me-2"></i>Vehículos (Listado)</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'vehiculos' && isset($_GET['action']) && $_GET['action'] == 'calendario' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Vehiculos&action=calendario') ?>"><i class="fas fa-calendar-alt me-2"></i>Vehículos (Calendario)</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'reservas' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Reservas&action=index') ?>"><i class="fas fa-calendar-check me-2"></i>Reservas</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'contratos' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Contratos&action=index') ?>"><i class="fas fa-file-contract me-2"></i>Contratos</a></li>
                    </ul>
                </li>

                <!-- Menú Operaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($seccion, ['devoluciones', 'checklist', 'mantenimientos', 'asistencia']) ? 'active' : '' ?>" 
                       href="#" id="operacionesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tools" style="color: #137fec;"></i> Operaciones
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #101922; border: 1px solid #137fec;">
                        <li><a class="dropdown-item <?= $seccion == 'devoluciones' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Devolucion&action=listado') ?>"><i class="fas fa-undo-alt me-2"></i>Devoluciones</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'mantenimientos' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Mantenimientos&action=index') ?>"><i class="fas fa-wrench me-2"></i>Mantenimientos</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'asistencia' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=AsistenciaAdmin&action=index') ?>"><i class="fas fa-clock me-2"></i>Asistencia</a></li>
                    </ul>
                </li>

                <!-- Reportes -->
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'reportes' ? 'active' : '' ?>" 
                       href="<?= url('index.php?area=admin&controller=Reportes&action=index') ?>">
                        <i class="fas fa-chart-bar" style="color: #137fec;"></i> Reportes
                    </a>
                </li>

                <!-- Administración (siempre visible) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($seccion, ['usuarios', 'multas']) ? 'active' : '' ?>" 
                       href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog" style="color: #137fec;"></i> Administración
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" style="background-color: #101922; border: 1px solid #137fec;">
                        <li><a class="dropdown-item <?= $seccion == 'usuarios' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Usuarios&action=index') ?>"><i class="fas fa-users-cog me-2"></i>Usuarios</a></li>
                        <li><a class="dropdown-item <?= $seccion == 'multas' ? 'active' : '' ?>" href="<?= url('index.php?area=admin&controller=Multas&action=index') ?>"><i class="fas fa-exclamation-triangle me-2"></i>Multas</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Menú de usuario -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" style="color: #ffffff;">
                        <i class="fas fa-user-circle me-2" style="color: #137fec; font-size: 1.5rem;"></i>
                        <span><?= htmlspecialchars($usuario) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" style="background-color: #101922; border: 1px solid #137fec;">
                        <li><a class="dropdown-item" href="<?= url('index.php?area=admin&controller=Perfil&action=index') ?>"><i class="fas fa-id-card me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider" style="border-color: #137fec;"></li>
                        <li><a class="dropdown-item text-danger" href="<?= url('index.php?area=admin&controller=Auth&action=logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 70px;"></div>