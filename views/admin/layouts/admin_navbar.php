<?php
$usuario = $_SESSION['admin_nombre'] ?? 'Admin';
$inicial = strtoupper(substr($usuario, 0, 1));
$seccion = $seccion ?? '';
$rol = $_SESSION['admin_rol'] ?? 'operador';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Sistema_RentACar/index.php?controller=Dashboard&action=index">
            <i class="fas fa-car-side me-2" style="color: #137fec;"></i>CarRent <small>Admin</small>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'dashboard' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Dashboard&action=index">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'clientes' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Clientes&action=index">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'devoluciones' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Devolucion&action=index">
                        <i class="fas fa-undo-alt"></i> Devoluciones
                    </a>
                </li>

                <li class="nav-item">
    <a class="nav-link <?= $seccion == 'mantenimientos' ? 'active' : '' ?>" 
       href="/Sistema_RentACar/index.php?controller=Mantenimientos&action=index">
        <i class="fas fa-wrench"></i> Mantenimientos
    </a>
</li>
                
                <?php if ($_SESSION['admin_rol'] === 'superadmin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'usuarios' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Usuarios&action=index">
                        <i class="fas fa-users-cog"></i> Usuarios
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['admin_rol'] === 'superadmin'): ?>
<li class="nav-item">
    <a class="nav-link <?= $seccion == 'multas' ? 'active' : '' ?>" 
       href="/Sistema_RentACar/index.php?controller=Multas&action=index">
        <i class="fas fa-exclamation-triangle"></i> Multas
    </a>
</li>
<?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'reservas' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Reservas&action=index">
                        <i class="fas fa-calendar-check"></i> Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'reportes' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Reportes&action=index">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= $seccion == 'contratos' ? 'active' : '' ?>" 
                       href="/Sistema_RentACar/index.php?controller=Contratos&action=index">
                        <i class="fas fa-file-contract"></i> Contratos
                    </a>
                </li>

                 <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle <?= strpos($seccion, 'vehiculo') !== false ? 'active' : '' ?>" href="#" id="vehiculosDropdown" role="button" data-bs-toggle="dropdown">
        <i class="fas fa-car"></i> Vehículos
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item <?= $seccion == 'vehiculos' && !isset($_GET['action']) ? 'active' : '' ?>" href="<?= url('index.php?controller=Vehiculos&action=index') ?>">Listado</a></li>
        <li><a class="dropdown-item <?= $seccion == 'vehiculos' && $_GET['action'] == 'calendario' ? 'active' : '' ?>" href="<?= url('index.php?controller=Vehiculos&action=calendario') ?>">Calendario</a></li>
    </ul>
</li>   


            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($usuario) ?> (<?= $rol ?>)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-id-card me-2"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/Sistema_RentACar/index.php?controller=Auth&action=logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 70px;"></div>