<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Clientes | CarWash RentaCar</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- CSS propio del módulo -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/AdministracionClientesOperaciones/clientes.css" />
</head>
<body>

<!-- ░░░ SIDEBAR ░░░ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">🚗</div>
    <span>CarWash<small>RentaCar</small></span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-title">Principal</div>
    <a href="#"><i class="fas fa-gauge-high"></i> Dashboard</a>

    <div class="nav-section-title">Clientes</div>
    <a href="<?= $BASE_URL ?>/index.php?v=clientes" class="active"><i class="fas fa-users"></i> Gestión de Clientes</a>

    <div class="nav-section-title">Vehículos</div>
    <a href="<?= $BASE_URL ?>/index.php?v=disponibilidad"><i class="fas fa-calendar-days"></i> Calendario</a>
    <a href="<?= $BASE_URL ?>/index.php?v=devolucion"><i class="fas fa-rotate-left"></i> Devolución</a>
    <a href="<?= $BASE_URL ?>/index.php?v=checklist"><i class="fas fa-clipboard-check"></i> Checklist</a>

    <div class="nav-section-title">Sistema</div>
    <a href="#"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</a>
  </nav>

  <div class="sidebar-footer">v1.0.0 &copy; 2024 CarWash</div>
</aside>

<!-- ░░░ MAIN ░░░ -->
<div class="main-content">

  <!-- TOPBAR -->
  <header class="topbar">
    <button class="btn-icon d-lg-none" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h1 class="topbar-title"><i class="fas fa-users me-2" style="color:var(--primary-light)"></i>Gestión de <span>Clientes</span></h1>
    <div class="topbar-actions">
      <button class="btn-icon"><i class="fas fa-bell"></i></button>
      <div class="user-chip">
        <div class="avatar">A</div>
        Admin
      </div>
    </div>
  </header>

  <!-- BODY -->
  <div class="page-body">

    <!-- STATS -->
    <div class="stats-row fade-up">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $totalClientes ?? 124 ?></div>
          <div class="stat-label">Total Clientes</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $activos ?? 98 ?></div>
          <div class="stat-label">Clientes Activos</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-user-plus"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $nuevosMes ?? 12 ?></div>
          <div class="stat-label">Nuevos este mes</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $inactivos ?? 26 ?></div>
          <div class="stat-label">Inactivos</div>
        </div>
      </div>
    </div>

    <!-- TABLA CLIENTES -->
    <div class="card fade-up delay-1">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-table-list"></i> Lista de Clientes</div>
        <div class="d-flex gap-2 flex-wrap">
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Buscar cliente...">
          </div>
          <button class="btn btn-primary btn-sm" onclick="openModal('modalNuevoCliente')">
            <i class="fas fa-plus"></i> Nuevo Cliente
          </button>
          <a href="#" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Exportar</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="data-table" id="clientesTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>DUI / NIT</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Renta activa</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr id="row-1">
                <td>001</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar" style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));display:flex;align-items:center;justify-content:center;font-weight:700;">JM</div>
                    <div>
                      <div style="font-weight:600;color:var(--white)">Juan Carlos Martínez</div>
                      <div style="font-size:.72rem;color:var(--text-muted)">Registrado: 12/01/2024</div>
                    </div>
                  </div>
                </td>
                <td>03456789-0</td>
                <td>7654-3210</td>
                <td>jmartinez@email.com</td>
                <td><span class="badge badge-pending"><i class="fas fa-circle" style="font-size:.5rem"></i> Toyota Hilux</span></td>
                <td><span class="badge badge-active"><i class="fas fa-circle" style="font-size:.5rem"></i> Activo</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="btn btn-outline btn-sm" onclick="editCliente(1)" title="Editar"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCliente(1)" title="Eliminar"><i class="fas fa-trash"></i></button>
                    <a href="#" class="btn btn-sm" style="background:rgba(0,180,216,0.1);border:1px solid var(--primary-light);color:var(--primary-light)" title="Ver historial"><i class="fas fa-eye"></i></a>
                  </div>
                </td>
              </tr>

              <tr id="row-2">
                <td>002</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar" style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;border-radius:50%;background:linear-gradient(135deg,#1a9e94,#2ec4b6);display:flex;align-items:center;justify-content:center;font-weight:700;">MR</div>
                    <div>
                      <div style="font-weight:600;color:var(--white)">María López Rivas</div>
                      <div style="font-size:.72rem;color:var(--text-muted)">Registrada: 03/03/2024</div>
                    </div>
                  </div>
                </td>
                <td>04567890-1</td>
                <td>6543-2109</td>
                <td>mlopez@email.com</td>
                <td><span class="badge" style="background:rgba(126,175,201,0.1);color:var(--text-muted);border:1px solid var(--card-border)">Sin renta</span></td>
                <td><span class="badge badge-active"><i class="fas fa-circle" style="font-size:.5rem"></i> Activo</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="btn btn-outline btn-sm" onclick="editCliente(2)"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCliente(2)"><i class="fas fa-trash"></i></button>
                    <a href="#" class="btn btn-sm" style="background:rgba(0,180,216,0.1);border:1px solid var(--primary-light);color:var(--primary-light)"><i class="fas fa-eye"></i></a>
                  </div>
                </td>
              </tr>

              <tr id="row-3">
                <td>003</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar" style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;border-radius:50%;background:linear-gradient(135deg,var(--warning),#e76f51);display:flex;align-items:center;justify-content:center;font-weight:700;">CR</div>
                    <div>
                      <div style="font-weight:600;color:var(--white)">Carlos Roberto Pérez</div>
                      <div style="font-size:.72rem;color:var(--text-muted)">Registrado: 20/02/2024</div>
                    </div>
                  </div>
                </td>
                <td>05678901-2</td>
                <td>7890-1234</td>
                <td>crperez@email.com</td>
                <td><span class="badge badge-pending"><i class="fas fa-circle" style="font-size:.5rem"></i> Honda CR-V</span></td>
                <td><span class="badge badge-inactive"><i class="fas fa-circle" style="font-size:.5rem"></i> Inactivo</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="btn btn-outline btn-sm" onclick="editCliente(3)"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCliente(3)"><i class="fas fa-trash"></i></button>
                    <a href="#" class="btn btn-sm" style="background:rgba(0,180,216,0.1);border:1px solid var(--primary-light);color:var(--primary-light)"><i class="fas fa-eye"></i></a>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div><!-- /card -->

  </div><!-- /page-body -->
</div><!-- /main-content -->

<!-- ░░░ MODAL: NUEVO CLIENTE ░░░ -->
<div class="modal-overlay" id="modalNuevoCliente">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Cliente</div>
      <button class="modal-close" onclick="closeModal('modalNuevoCliente')"><i class="fas fa-times"></i></button>
    </div>
    <form action="<?= $BASE_URL ?>/controller/AdministracionClientesOperaciones/ClienteController.php" method="POST">
      <input type="hidden" name="action" value="crear">
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombre *</label>
            <input class="form-control" type="text" name="nombre" placeholder="Nombre(s)" required>
          </div>
          <div class="form-group">
            <label class="form-label">Apellido *</label>
            <input class="form-control" type="text" name="apellido" placeholder="Apellido(s)" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">DUI *</label>
            <input class="form-control" type="text" name="dui" placeholder="00000000-0" required>
          </div>
          <div class="form-group">
            <label class="form-label">NIT</label>
            <input class="form-control" type="text" name="nit" placeholder="0000-000000-000-0">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Teléfono *</label>
            <input class="form-control" type="tel" name="telefono" placeholder="0000-0000" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" placeholder="correo@ejemplo.com">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Dirección</label>
          <input class="form-control" type="text" name="direccion" placeholder="Dirección completa">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tipo de Licencia</label>
            <select class="form-control" name="tipo_licencia">
              <option value="">Seleccionar</option>
              <option value="liviana">Liviana</option>
              <option value="pesada">Pesada</option>
              <option value="motocicleta">Motocicleta</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">N° Licencia</label>
            <input class="form-control" type="text" name="no_licencia" placeholder="Número de licencia">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Estado</label>
          <select class="form-control" name="estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Observaciones</label>
          <textarea class="form-control" name="observaciones" rows="2" placeholder="Notas adicionales..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalNuevoCliente')">Cancelar</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cliente</button>
      </div>
    </form>
  </div>
</div>

<!-- ░░░ MODAL: EDITAR CLIENTE ░░░ -->
<div class="modal-overlay" id="modalEditCliente">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-pen-to-square"></i> Editar Cliente</div>
      <button class="modal-close" onclick="closeModal('modalEditCliente')"><i class="fas fa-times"></i></button>
    </div>
    <form action="<?= $BASE_URL ?>/controller/AdministracionClientesOperaciones/ClienteController.php" method="POST">
      <input type="hidden" name="action" value="editar">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombre *</label>
            <input class="form-control" type="text" name="nombre" id="edit_nombre" required>
          </div>
          <div class="form-group">
            <label class="form-label">Apellido *</label>
            <input class="form-control" type="text" name="apellido" id="edit_apellido" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">DUI</label>
            <input class="form-control" type="text" name="dui" id="edit_dui">
          </div>
          <div class="form-group">
            <label class="form-label">NIT</label>
            <input class="form-control" type="text" name="nit" id="edit_nit">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Teléfono</label>
            <input class="form-control" type="tel" name="telefono" id="edit_telefono">
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" id="edit_email">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Dirección</label>
          <input class="form-control" type="text" name="direccion" id="edit_direccion">
        </div>
        <div class="form-group">
          <label class="form-label">Estado</label>
          <select class="form-control" name="estado" id="edit_estado">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalEditCliente')">Cancelar</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
      </div>
    </form>
  </div>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS propio del módulo -->
<script src="<?= $BASE_URL ?>/assets/js/AdministracionClientesOperaciones/clientes.js"></script>
</body>
</html>