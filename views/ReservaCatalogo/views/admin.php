<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | GO CAR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Rent-a-car/assets/css/style.css">
    <link rel="stylesheet" href="/Rent-a-car/assets/css/admin.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <section class="admin-page py-5">
        <div class="container">

            <!-- Encabezado -->
            <div class="admin-header">
                <div>
                    <h1 class="admin-title">Panel de Reservas</h1>
                    <p class="admin-subtitle">Administra y monitorea todas las reservas de vehículos</p>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="statTotalReservations">0</span>
                        <span class="stat-label">Reservas Totales</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="statTotalRevenue">$0.00</span>
                        <span class="stat-label">Ingresos Totales</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon stat-icon-active">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value" id="statActiveReservations">0</span>
                        <span class="stat-label">Reservas Activas</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de Reservas -->
            <div class="admin-table-card">
                <div class="admin-table-header">
                    <h3>Todas las Reservas</h3>
                </div>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehículo</th>
                                <th>Fecha</th>
                                <th>Pago</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody">
                            <!-- Las filas son renderizadas por script.js -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Rent-a-car/assets/js/script.js"></script>
</body>

</html>