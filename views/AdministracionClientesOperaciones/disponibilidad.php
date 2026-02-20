<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Disponibilidad de Vehículos | CarWash RentaCar</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- SIEMPRE con BASE_URL -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/AdministracionClientesOperaciones/clientes.css" />
</head>
<body>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">🚗</div>
    <span>CarWash<small>RentaCar</small></span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-title">Principal</div>
    <a href="#"><i class="fas fa-gauge-high"></i> Dashboard</a>

    <div class="nav-section-title">Clientes</div>
    <a href="<?= $BASE_URL ?>/index.php?v=clientes"><i class="fas fa-users"></i> Gestión de Clientes</a>

    <div class="nav-section-title">Vehículos</div>
    <a href="<?= $BASE_URL ?>/index.php?v=disponibilidad" class="active"><i class="fas fa-calendar-days"></i> Calendario</a>
    <a href="<?= $BASE_URL ?>/index.php?v=devolucion"><i class="fas fa-rotate-left"></i> Devolución</a>
    <a href="<?= $BASE_URL ?>/index.php?v=checklist"><i class="fas fa-clipboard-check"></i> Checklist</a>

    <div class="nav-section-title">Sistema</div>
    <a href="#"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</a>
  </nav>

  <div class="sidebar-footer">v1.0.0 &copy; 2024 CarWash</div>
</aside>

<div class="main-content">
  <header class="topbar">
    <button class="btn-icon d-lg-none" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h1 class="topbar-title"><i class="fas fa-calendar-days me-2" style="color:var(--primary-light)"></i>Disponibilidad de <span>Vehículos</span></h1>
    <div class="topbar-actions">
      <button class="btn-icon"><i class="fas fa-bell"></i></button>
      <div class="user-chip"><div class="avatar">A</div>Admin</div>
    </div>
  </header>

  <div class="page-body">

    <!-- STATS VEHICULOS -->
    <div class="stats-row fade-up">
      <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-car"></i></div>
        <div class="stat-info">
          <div class="stat-number">8</div>
          <div class="stat-label">Disponibles</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-key"></i></div>
        <div class="stat-info">
          <div class="stat-number">5</div>
          <div class="stat-label">En Renta</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-wrench"></i></div>
        <div class="stat-info">
          <div class="stat-number">2</div>
          <div class="stat-label">Mantenimiento</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon cyan"><i class="fas fa-car-on"></i></div>
        <div class="stat-info">
          <div class="stat-number">15</div>
          <div class="stat-label">Total Flota</div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- CALENDARIO -->
      <div class="col-lg-8 fade-up delay-1">
        <div class="calendar-wrapper">
          <div class="calendar-nav">
            <button class="btn btn-outline btn-sm" onclick="CalendarApp.prevMonth()"><i class="fas fa-chevron-left"></i></button>
            <div class="calendar-nav-title" id="calendarTitle"></div>
            <button class="btn btn-outline btn-sm" onclick="CalendarApp.nextMonth()"><i class="fas fa-chevron-right"></i></button>
          </div>

          <div class="calendar-grid" id="calendarHeaders">
            <div class="cal-day-header">Dom</div>
            <div class="cal-day-header">Lun</div>
            <div class="cal-day-header">Mar</div>
            <div class="cal-day-header">Mié</div>
            <div class="cal-day-header">Jue</div>
            <div class="cal-day-header">Vie</div>
            <div class="cal-day-header">Sáb</div>
          </div>

          <div class="calendar-grid" id="calendarGrid"></div>

          <div style="padding:14px 20px;display:flex;gap:16px;flex-wrap:wrap;border-top:1px solid var(--card-border)">
            <div style="display:flex;align-items:center;gap:6px;font-size:.77rem;color:var(--text-muted)">
              <div style="width:14px;height:14px;border-radius:3px;background:rgba(46,196,182,0.25)"></div> Disponible
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:.77rem;color:var(--text-muted)">
              <div style="width:14px;height:14px;border-radius:3px;background:rgba(0,119,182,0.35)"></div> Reservado
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:.77rem;color:var(--text-muted)">
              <div style="width:14px;height:14px;border-radius:3px;background:rgba(230,57,70,0.25)"></div> Ocupado
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:.77rem;color:var(--text-muted)">
              <div style="width:14px;height:14px;border-radius:3px;background:rgba(244,162,97,0.25)"></div> Mantenimiento
            </div>
          </div>
        </div>
      </div>

      <!-- PANEL LATERAL: FLOTA -->
      <div class="col-lg-4 fade-up delay-2">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="fas fa-car-side"></i> Flota de Vehículos</div>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalFiltro')">
              <i class="fas fa-filter"></i>
            </button>
          </div>
          <div class="card-body p-2">
            <div class="vehicle-grid" style="grid-template-columns:1fr 1fr">

              <div class="vehicle-card" onclick="openModal('modalVehicle')">
                <div class="vehicle-icon">🚙</div>
                <div class="vehicle-plate">P-1234</div>
                <div class="vehicle-info">
                  <div><strong>Toyota Hilux</strong></div>
                  <div>2022 · Plateado</div>
                  <div style="margin-top:6px">
                    <span class="badge badge-active"><i class="fas fa-circle" style="font-size:.4rem"></i> Disponible</span>
                  </div>
                </div>
              </div>

              <div class="vehicle-card">
                <div class="vehicle-icon">🚗</div>
                <div class="vehicle-plate">P-5678</div>
                <div class="vehicle-info">
                  <div><strong>Honda CR-V</strong></div>
                  <div>2021 · Negro</div>
                  <div style="margin-top:6px">
                    <span class="badge badge-inactive"><i class="fas fa-circle" style="font-size:.4rem"></i> En renta</span>
                  </div>
                </div>
              </div>

              <div class="vehicle-card">
                <div class="vehicle-icon">🚐</div>
                <div class="vehicle-plate">P-9012</div>
                <div class="vehicle-info">
                  <div><strong>Hyundai H1</strong></div>
                  <div>2023 · Blanco</div>
                  <div style="margin-top:6px">
                    <span class="badge badge-pending"><i class="fas fa-circle" style="font-size:.4rem"></i> Mantenim.</span>
                  </div>
                </div>
              </div>

              <div class="vehicle-card">
                <div class="vehicle-icon">🚙</div>
                <div class="vehicle-plate">P-3456</div>
                <div class="vehicle-info">
                  <div><strong>Kia Sportage</strong></div>
                  <div>2022 · Rojo</div>
                  <div style="margin-top:6px">
                    <span class="badge badge-active"><i class="fas fa-circle" style="font-size:.4rem"></i> Disponible</span>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div><!-- /row -->

  </div>
</div>

<!-- MODAL: DETALLE DÍA -->
<div class="modal-overlay" id="modalDayDetail">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-calendar-day"></i> Detalle del Día</div>
      <button class="modal-close" onclick="closeModal('modalDayDetail')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <p id="calDayLabel" style="color:var(--text-muted);margin-bottom:16px"></p>

      <div style="margin-bottom:16px">
        <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:10px;font-weight:600">Vehículos del día</div>
        <div class="d-flex flex-column gap-2">
          <div style="background:rgba(0,0,0,0.3);border:1px solid var(--card-border);border-radius:8px;padding:12px 14px;display:flex;align-items:center;gap:12px">
            <span>🚙</span>
            <div>
              <div style="font-weight:600;color:var(--white)">P-1234 · Toyota Hilux</div>
              <div style="font-size:.75rem;color:var(--text-muted)">Cliente: Juan Martínez &bull; 08:00 - 18:00</div>
            </div>
            <span class="badge badge-pending ms-auto">Reservado</span>
          </div>
          <div style="background:rgba(0,0,0,0.3);border:1px solid var(--card-border);border-radius:8px;padding:12px 14px;display:flex;align-items:center;gap:12px">
            <span>🚗</span>
            <div>
              <div style="font-weight:600;color:var(--white)">P-3456 · Kia Sportage</div>
              <div style="font-size:.75rem;color:var(--text-muted)">Disponible todo el día</div>
            </div>
            <span class="badge badge-active ms-auto">Disponible</span>
          </div>
        </div>
      </div>

      <!-- OJO: antes decía devolucion.php; aquí solo te mando a una vista del router -->
      <a href="<?= $BASE_URL ?>/index.php?v=devolucion" class="btn btn-primary w-100">
        <i class="fas fa-plus"></i> Nueva Reserva este día
      </a>
    </div>
  </div>
</div>

<!-- MODAL: DETALLE VEHÍCULO -->
<div class="modal-overlay" id="modalVehicle">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title"><i class="fas fa-car"></i> P-1234 · Toyota Hilux 2022</div>
      <button class="modal-close" onclick="closeModal('modalVehicle')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div class="form-row" style="margin-bottom:14px">
        <div><span class="form-label">Tipo</span><p style="color:var(--white)">Pickup Doble Cabina</p></div>
        <div><span class="form-label">Color</span><p style="color:var(--white)">Plateado</p></div>
        <div><span class="form-label">Año</span><p style="color:var(--white)">2022</p></div>
        <div><span class="form-label">KM actual</span><p style="color:var(--white)">42,500 km</p></div>
      </div>
      <div><span class="form-label">Próximo mantenimiento</span>
        <div class="progress-bar-wrapper mt-1"><div class="progress-bar" style="width:72%"></div></div>
        <small style="color:var(--text-muted)">45,000 km · 72% del ciclo</small>
      </div>
      <div class="mt-3">
        <span class="form-label">Observaciones</span>
        <p style="color:var(--text-muted);font-size:.87rem">Vehículo en excelentes condiciones. Último cambio de aceite: 10/01/2024.</p>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-outline" onclick="closeModal('modalVehicle')">Cerrar</button>
      <a href="<?= $BASE_URL ?>/index.php?v=checklist" class="btn btn-primary">
        <i class="fas fa-clipboard-check"></i> Ver Checklist
      </a>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SIEMPRE con BASE_URL -->
<script src="<?= $BASE_URL ?>/assets/js/AdministracionClientesOperaciones/clientes.js"></script>

</body>
</html>