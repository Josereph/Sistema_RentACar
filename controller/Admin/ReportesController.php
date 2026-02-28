<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';

class ReportesController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $db = Database::connect();

        // ============================================
        // 1. KPIs rápidos
        // ============================================
        $hoy = date('Y-m-d');
        $inicioMes = date('Y-m-01');
        $finMes = date('Y-m-t');
        $inicioAnio = date('Y-01-01');
        $finAnio = date('Y-12-31');
        $inicio12 = date('Y-m-d', strtotime('-12 months'));

        // Reservas hoy
        $reservasHoy = $db->query("SELECT COUNT(*) FROM tbReservas WHERE DATE(fecha_reserva) = '$hoy'")->fetchColumn();

        // Ingresos hoy, mes, año
        $ingresosHoy = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbPagos WHERE estado = 'pagado' AND DATE(created_at) = '$hoy'")->fetchColumn();
        $ingresosMes = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbPagos WHERE estado = 'pagado' AND DATE(created_at) BETWEEN '$inicioMes' AND '$finMes'")->fetchColumn();
        $ingresosAnio = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbPagos WHERE estado = 'pagado' AND DATE(created_at) BETWEEN '$inicioAnio' AND '$finAnio'")->fetchColumn();

        // Ingresos por concepto (último año)
        $ingresosPorConcepto = $db->query("
            SELECT concepto, SUM(pd.monto) as total
            FROM tb_pago_detalle pd
            INNER JOIN tbPagos p ON pd.id_pago = p.id_pago
            WHERE p.estado = 'pagado' AND p.created_at >= '$inicio12'
            GROUP BY concepto
        ")->fetchAll();

        // Ticket promedio
        $ticketPromedio = $db->query("SELECT COALESCE(AVG(monto), 0) FROM tbPagos WHERE estado = 'pagado'")->fetchColumn();

        // Tasas
        $totalReservas = $db->query("SELECT COUNT(*) FROM tbReservas")->fetchColumn();
        $canceladas = $db->query("SELECT COUNT(*) FROM tbReservas WHERE estado = 'cancelada'")->fetchColumn();
        $tasaCancelacion = $totalReservas > 0 ? round(($canceladas / $totalReservas) * 100, 2) : 0;
        $noCanceladas = $totalReservas - $canceladas;
        $tasaConversion = $totalReservas > 0 ? round(($noCanceladas / $totalReservas) * 100, 2) : 0;

        $totalPagos = $db->query("SELECT COUNT(*) FROM tbPagos")->fetchColumn();
        $pendientes = $db->query("SELECT COUNT(*) FROM tbPagos WHERE estado IN ('pendiente', 'fallido')")->fetchColumn();
        $tasaMorosidad = $totalPagos > 0 ? round(($pendientes / $totalPagos) * 100, 2) : 0;

        // Flota
        $flotaDisponible = $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'disponible'")->fetchColumn();
        $flotaRentada = $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'rentado'")->fetchColumn();
        $flotaMantenimiento = $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'mantenimiento'")->fetchColumn();
        $totalVehiculos = $flotaDisponible + $flotaRentada + $flotaMantenimiento;

        // Utilización de flota
        $utilizacion = $this->calcularUtilizacionFlota();

        // Top 5 vehículos más rentados
        $topVehiculosRentados = $db->query("
            SELECT v.id_vehiculo, v.marca, v.modelo, v.numero_placa, COUNT(r.id_reserva) as total_reservas
            FROM tbReservas r
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            GROUP BY r.id_vehiculo
            ORDER BY total_reservas DESC
            LIMIT 5
        ")->fetchAll();

        // Top 5 vehículos con mayor ingreso
        $topVehiculosIngresos = $db->query("
            SELECT v.id_vehiculo, v.marca, v.modelo, v.numero_placa, COALESCE(SUM(p.monto), 0) as total_ingresos
            FROM tbReservas r
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
            GROUP BY r.id_vehiculo
            ORDER BY total_ingresos DESC
            LIMIT 5
        ")->fetchAll();

        // Top 5 clientes por gasto
        $topClientesGasto = $db->query("
            SELECT c.id_cliente, c.nombre, c.apellido, c.correo, COALESCE(SUM(p.monto), 0) as total_gastado
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
            GROUP BY r.id_cliente
            ORDER BY total_gastado DESC
            LIMIT 5
        ")->fetchAll();

        // Multas
        $totalMultas = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbMultas")->fetchColumn();
        $multasPagadas = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbMultas WHERE pagada = 1")->fetchColumn();
        $porcentajeMultasPagadas = $totalMultas > 0 ? round(($multasPagadas / $totalMultas) * 100, 2) : 0;

        // Mantenimientos
        $costoMantenimientoAnual = $db->query("SELECT COALESCE(SUM(costo), 0) FROM tb_mantenimientos WHERE fecha_inicio >= '$inicio12'")->fetchColumn();
        $fallasChecklist = $db->query("SELECT COUNT(*) FROM tbChecklistInspeccion WHERE estado = 'falla' AND created_at >= '$inicio12'")->fetchColumn();
        $tiempoPromedioMantenimiento = $db->query("SELECT COALESCE(AVG(DATEDIFF(fecha_fin, fecha_inicio)), 0) FROM tb_mantenimientos WHERE fecha_fin IS NOT NULL")->fetchColumn();

        // Próximas entregas
        $manana = date('Y-m-d', strtotime('+1 day'));
        $tresDias = date('Y-m-d', strtotime('+3 days'));
        $proximasEntregas = $db->query("
            SELECT r.id_reserva, c.nombre, c.apellido, v.marca, v.modelo, r.fecha_entrega
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE r.estado = 'en_curso' AND DATE(r.fecha_entrega) BETWEEN '$manana' AND '$tresDias'
            ORDER BY r.fecha_entrega
        ")->fetchAll();

        // ============================================
        // 2. Datos para gráficos (opcional)
        // ============================================
        $reservasPorEstado = $db->query("SELECT estado, COUNT(*) as total FROM tbReservas GROUP BY estado")->fetchAll();
        $reservasPorMes = $db->query("
            SELECT DATE_FORMAT(fecha_reserva, '%Y-%m') as mes, COUNT(*) as total
            FROM tbReservas
            WHERE fecha_reserva >= '$inicio12'
            GROUP BY mes
            ORDER BY mes
        ")->fetchAll();
        $ingresosPorMes = $db->query("
            SELECT DATE_FORMAT(p.created_at, '%Y-%m') as mes, SUM(p.monto) as total
            FROM tbPagos p
            WHERE p.estado = 'pagado' AND p.created_at >= '$inicio12'
            GROUP BY mes
            ORDER BY mes
        ")->fetchAll();

        // ============================================
        // Pasar variables a la vista
        // ============================================
        $titulo = 'Reportes Estadísticos';
        $seccion = 'reportes';
        require PROJECT_ROOT_FS . '/views/admin/reportes/index.php';
    }

    private function calcularUtilizacionFlota()
    {
        $db = Database::connect();
        $inicioMes = date('Y-m-01');
        $finMes = date('Y-m-t');
        $diasMes = (strtotime($finMes) - strtotime($inicioMes)) / 86400 + 1;

        $diasRentados = $db->query("
            SELECT COALESCE(SUM(DATEDIFF(LEAST(fecha_entrega, '$finMes'), GREATEST(fecha_recogida, '$inicioMes')) + 1), 0)
            FROM tbReservas
            WHERE fecha_recogida <= '$finMes' AND fecha_entrega >= '$inicioMes'
              AND estado IN ('en_curso', 'completada')
        ")->fetchColumn();

        $totalVehiculos = $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();
        if ($totalVehiculos == 0 || $diasMes == 0) return 0;
        return round(($diasRentados / ($totalVehiculos * $diasMes)) * 100, 2);
    }

    // ============================================
    // NUEVOS MÉTODOS PARA REPORTES AVANZADOS
    // ============================================

    public function avanzado()
    {
        parent::__construct();
        $titulo = 'Reportes Avanzados';
        $seccion = 'reportes';
        require PROJECT_ROOT_FS . '/views/admin/reportes/avanzado.php';
    }

    public function generarPdfAvanzado()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Método no permitido');
        }

        $tipo = $_POST['tipo'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        require_once __DIR__ . '/../../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf();

        $db = Database::connect();
        $html = $this->generarHtmlReporte($tipo, $fecha_inicio, $fecha_fin, $db);

        $mpdf->WriteHTML($html);
        $mpdf->Output('reporte_' . $tipo . '_' . date('Y-m-d') . '.pdf', 'D');
        exit;
    }

    private function generarHtmlReporte($tipo, $fecha_inicio, $fecha_fin, $db)
    {
        $html = '<h1>Reporte ' . ucfirst(str_replace('_', ' ', $tipo)) . '</h1>';
        if ($fecha_inicio && $fecha_fin) {
            $html .= '<p>Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($fecha_fin)) . '</p>';
        }

        switch ($tipo) {
            // ==================== 1. Dashboard ejecutivo (KPIs) ====================
            case 'kpi_reservas_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbReservas GROUP BY estado")->fetchAll();
                $html .= '<h2>Reservas por estado</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_ingresos_por_concepto':
                $sql = "SELECT concepto, SUM(pd.monto) as total
                        FROM tb_pago_detalle pd
                        INNER JOIN tbPagos p ON pd.id_pago = p.id_pago
                        WHERE p.estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND p.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin 23:59:59'";
                }
                $sql .= " GROUP BY concepto";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por concepto</h2><table border="1" cellpadding="5"><tr><th>Concepto</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['concepto']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_flota_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbVehiculos GROUP BY estado")->fetchAll();
                $html .= '<h2>Estado de la flota</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Cantidad</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_utilizacion_flota':
                $inicio = $fecha_inicio ?: date('Y-m-01');
                $fin = $fecha_fin ?: date('Y-m-t');
                $dias = (strtotime($fin) - strtotime($inicio)) / 86400 + 1;
                $diasRentados = $db->query("
                    SELECT COALESCE(SUM(DATEDIFF(LEAST(fecha_entrega, '$fin'), GREATEST(fecha_recogida, '$inicio')) + 1), 0)
                    FROM tbReservas
                    WHERE fecha_recogida <= '$fin' AND fecha_entrega >= '$inicio'
                      AND estado IN ('en_curso', 'completada')
                ")->fetchColumn();
                $totalVehiculos = $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();
                $utilizacion = $totalVehiculos > 0 ? round(($diasRentados / ($totalVehiculos * $dias)) * 100, 2) : 0;
                $html .= "<h2>Utilización de flota</h2><p>Período: " . date('d/m/Y', strtotime($inicio)) . " - " . date('d/m/Y', strtotime($fin)) . "</p>";
                $html .= "<p><strong>Utilización:</strong> $utilizacion%</p>";
                break;

            case 'kpi_ingresos_por_dia':
                $sql = "SELECT DATE(created_at) as dia, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(created_at) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY dia ORDER BY dia";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por día</h2><table border="1" cellpadding="5"><tr><th>Fecha</th><th>Ingresos</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['dia']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_ingresos_por_semana':
                $sql = "SELECT YEAR(created_at) as anio, WEEK(created_at) as semana, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(created_at) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY anio, semana ORDER BY anio, semana";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por semana</h2><table border="1" cellpadding="5"><tr><th>Año-Semana</th><th>Ingresos</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['anio']}-{$row['semana']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_ingresos_por_mes':
                $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(created_at) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY mes ORDER BY mes";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por mes</h2><table border="1" cellpadding="5"><tr><th>Mes</th><th>Ingresos</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['mes']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_ingresos_por_anio':
                $sql = "SELECT YEAR(created_at) as anio, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(created_at) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY anio ORDER BY anio";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por año</h2><table border="1" cellpadding="5"><tr><th>Año</th><th>Ingresos</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['anio']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'kpi_promedio_reservas_dia':
                $sql = "SELECT AVG(reservas) as promedio FROM (
                            SELECT DATE(fecha_reserva) as dia, COUNT(*) as reservas
                            FROM tbReservas
                            WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(fecha_reserva) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY dia
                        ) t";
                $promedio = $db->query($sql)->fetchColumn() ?: 0;
                $html .= "<h2>Promedio de reservas por día</h2>";
                $html .= "<p><strong>Promedio:</strong> " . round($promedio, 2) . " reservas/día</p>";
                break;

            case 'kpi_tasa_morosidad':
                $totalPagos = $db->query("SELECT COUNT(*) FROM tbPagos")->fetchColumn();
                $fallidos = $db->query("SELECT COUNT(*) FROM tbPagos WHERE estado IN ('pendiente','fallido')")->fetchColumn();
                $tasa = $totalPagos > 0 ? round(($fallidos / $totalPagos) * 100, 2) : 0;
                $html .= "<h2>Tasa de morosidad</h2><p><strong>$tasa%</strong> de pagos pendientes o fallidos.</p>";
                break;

            case 'kpi_tasa_conversion':
                $totalReservas = $db->query("SELECT COUNT(*) FROM tbReservas")->fetchColumn();
                $completadas = $db->query("SELECT COUNT(*) FROM tbReservas WHERE estado IN ('confirmada','en_curso','completada')")->fetchColumn();
                $tasa = $totalReservas > 0 ? round(($completadas / $totalReservas) * 100, 2) : 0;
                $html .= "<h2>Tasa de conversión</h2><p><strong>$tasa%</strong> de reservas no canceladas.</p>";
                break;

            case 'kpi_ingresos_netos_por_concepto':
                // Similar a ingresos por concepto pero más detalle (ya cubierto)
                $sql = "SELECT concepto, SUM(pd.monto) as total
                        FROM tb_pago_detalle pd
                        INNER JOIN tbPagos p ON pd.id_pago = p.id_pago
                        WHERE p.estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND p.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY concepto";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos netos por concepto</h2><table border="1" cellpadding="5"><tr><th>Concepto</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['concepto']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 2. Reservas ====================
            case 'reservas_por_dia':
                $sql = "SELECT DATE(fecha_reserva) as dia, COUNT(*) as total
                        FROM tbReservas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(fecha_reserva) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY dia ORDER BY dia";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por día</h2><table border="1" cellpadding="5"><tr><th>Fecha</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['dia']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_vehiculo':
                $sql = "SELECT v.marca, v.modelo, v.numero_placa, COUNT(r.id_reserva) as total
                        FROM tbReservas r
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_vehiculo ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por vehículo</h2><table border="1" cellpadding="5"><tr><th>Vehículo</th><th>Placa</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'duracion_promedio_renta':
                $sql = "SELECT AVG(DATEDIFF(fecha_entrega, fecha_recogida)) as promedio
                        FROM tbReservas
                        WHERE fecha_entrega > fecha_recogida";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $promedio = $db->query($sql)->fetchColumn() ?: 0;
                $html .= "<h2>Duración promedio de renta</h2><p><strong>Promedio:</strong> " . round($promedio, 2) . " días</p>";
                break;

            case 'reservas_por_hora':
                $sql = "SELECT HOUR(fecha_reserva) as hora, COUNT(*) as total
                        FROM tbReservas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(fecha_reserva) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY hora ORDER BY hora";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por hora del día</h2><table border="1" cellpadding="5"><tr><th>Hora</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['hora']}:00</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_dia_semana':
                $sql = "SELECT DAYOFWEEK(fecha_reserva) as dia_num, 
                               CASE DAYOFWEEK(fecha_reserva)
                                   WHEN 1 THEN 'Domingo'
                                   WHEN 2 THEN 'Lunes'
                                   WHEN 3 THEN 'Martes'
                                   WHEN 4 THEN 'Miércoles'
                                   WHEN 5 THEN 'Jueves'
                                   WHEN 6 THEN 'Viernes'
                                   WHEN 7 THEN 'Sábado'
                               END as dia,
                               COUNT(*) as total
                        FROM tbReservas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(fecha_reserva) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY dia_num ORDER BY dia_num";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por día de la semana</h2><table border="1" cellpadding="5"><tr><th>Día</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['dia']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_tipo_vehiculo':
                $sql = "SELECT v.tipo_vehiculo, COUNT(r.id_reserva) as total
                        FROM tbReservas r
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY v.tipo_vehiculo ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por tipo de vehículo</h2><table border="1" cellpadding="5"><tr><th>Tipo</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['tipo_vehiculo']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_marca':
                $sql = "SELECT v.marca, COUNT(r.id_reserva) as total
                        FROM tbReservas r
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY v.marca ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por marca</h2><table border="1" cellpadding="5"><tr><th>Marca</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_usuario':
                $sql = "SELECT u.nombre, COUNT(r.id_reserva) as total
                        FROM tbReservas r
                        LEFT JOIN tbUsuarios u ON r.id_usuario = u.id_usuario
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_usuario ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por usuario (empleado que gestionó)</h2><table border="1" cellpadding="5"><tr><th>Usuario</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_por_rango_duracion':
                $sql = "SELECT 
                            CASE 
                                WHEN DATEDIFF(fecha_entrega, fecha_recogida) <= 2 THEN '1-2 días'
                                WHEN DATEDIFF(fecha_entrega, fecha_recogida) <= 7 THEN '3-7 días'
                                WHEN DATEDIFF(fecha_entrega, fecha_recogida) <= 15 THEN '8-15 días'
                                ELSE 'Más de 15 días'
                            END as rango,
                            COUNT(*) as total
                        FROM tbReservas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND fecha_recogida BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY rango ORDER BY MIN(DATEDIFF(fecha_entrega, fecha_recogida))";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas por rango de duración</h2><table border="1" cellpadding="5"><tr><th>Duración</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['rango']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'reservas_confirmadas_sin_pago':
                $sql = "SELECT r.id_reserva, c.nombre, c.apellido, r.precio_total, r.fecha_recogida
                        FROM tbReservas r
                        INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                        LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                        WHERE r.estado = 'confirmada' AND p.id_pago IS NULL";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Reservas confirmadas sin pago</h2><table border="1" cellpadding="5"><tr><th>Reserva</th><th>Cliente</th><th>Monto</th><th>Recogida</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_reserva']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>$" . number_format($row['precio_total'], 2) . "</td><td>{$row['fecha_recogida']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 3. Ingresos y Pagos ====================
            case 'pagos_por_metodo':
                $sql = "SELECT metodo, COUNT(*) as cantidad, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY metodo";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Pagos por método</h2><table border="1" cellpadding="5"><tr><th>Método</th><th>Cantidad</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['metodo']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'pagos_por_proveedor':
                $sql = "SELECT proveedor, COUNT(*) as cantidad, SUM(monto) as total
                        FROM tbPagos
                        WHERE estado = 'pagado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY proveedor";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Pagos por proveedor</h2><table border="1" cellpadding="5"><tr><th>Proveedor</th><th>Cantidad</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['proveedor']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'pagos_fallidos':
                $sql = "SELECT * FROM tbPagos WHERE estado IN ('pendiente','fallido')";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Pagos pendientes/fallidos</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Reserva</th><th>Monto</th><th>Método</th><th>Estado</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_pago']}</td><td>{$row['id_reserva']}</td><td>$" . number_format($row['monto'], 2) . "</td><td>{$row['metodo']}</td><td>{$row['estado']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'pagos_reembolsados':
                $sql = "SELECT * FROM tbPagos WHERE estado = 'reembolsado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Pagos reembolsados</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Reserva</th><th>Monto</th><th>Método</th><th>Referencia</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_pago']}</td><td>{$row['id_reserva']}</td><td>$" . number_format($row['monto'], 2) . "</td><td>{$row['metodo']}</td><td>{$row['referencia']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'pagos_sin_referencia':
                $sql = "SELECT * FROM tbPagos WHERE referencia IS NULL OR referencia = ''";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Pagos sin referencia</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Reserva</th><th>Monto</th><th>Método</th><th>Estado</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_pago']}</td><td>{$row['id_reserva']}</td><td>$" . number_format($row['monto'], 2) . "</td><td>{$row['metodo']}</td><td>{$row['estado']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'detalle_ingresos_por_cliente':
                $sql = "SELECT c.id_cliente, c.nombre, c.apellido, SUM(p.monto) as total_pagado, COUNT(DISTINCT r.id_reserva) as reservas
                        FROM tbClientes c
                        LEFT JOIN tbReservas r ON c.id_cliente = r.id_cliente
                        LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND p.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY c.id_cliente HAVING total_pagado > 0 ORDER BY total_pagado DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por cliente</h2><table border="1" cellpadding="5"><tr><th>Cliente</th><th>Reservas</th><th>Total pagado</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['reservas']}</td><td>$" . number_format($row['total_pagado'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'detalle_ingresos_por_reserva':
                $sql = "SELECT r.id_reserva, c.nombre, c.apellido, v.marca, v.modelo, SUM(p.monto) as total_pagado
                        FROM tbReservas r
                        INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_reserva ORDER BY total_pagado DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Ingresos por reserva</h2><table border="1" cellpadding="5"><tr><th>Reserva</th><th>Cliente</th><th>Vehículo</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_reserva']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['marca']} {$row['modelo']}</td><td>$" . number_format($row['total_pagado'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 4. Contratos ====================
            case 'contratos_por_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbContratos GROUP BY estado")->fetchAll();
                $html .= '<h2>Contratos por estado</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'contratos_sin_pdf':
                $data = $db->query("SELECT id_contrato, numero_contrato FROM tbContratos WHERE pdf_path IS NULL")->fetchAll();
                $html .= '<h2>Contratos sin PDF</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Número</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_contrato']}</td><td>{$row['numero_contrato']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'contratos_activos':
                $sql = "SELECT c.*, r.fecha_recogida, r.fecha_entrega, cl.nombre, cl.apellido, v.marca, v.modelo
                        FROM tbContratos c
                        INNER JOIN tbReservas r ON c.id_reserva = r.id_reserva
                        INNER JOIN tbClientes cl ON r.id_cliente = cl.id_cliente
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE c.estado = 'activo'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND c.fecha_contrato BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Contratos activos</h2><table border="1" cellpadding="5"><tr><th>N°</th><th>Cliente</th><th>Vehículo</th><th>Depósito</th><th>PDF</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['numero_contrato']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['marca']} {$row['modelo']}</td><td>$" . number_format($row['deposito'], 2) . "</td><td>" . ($row['pdf_path'] ? 'Sí' : 'No') . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'contratos_finalizados':
                $sql = "SELECT c.*, r.fecha_recogida, r.fecha_entrega, cl.nombre, cl.apellido
                        FROM tbContratos c
                        INNER JOIN tbReservas r ON c.id_reserva = r.id_reserva
                        INNER JOIN tbClientes cl ON r.id_cliente = cl.id_cliente
                        WHERE c.estado = 'finalizado'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND c.fecha_contrato BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Contratos finalizados</h2><table border="1" cellpadding="5"><tr><th>N°</th><th>Cliente</th><th>Fecha contrato</th><th>Depósito</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['numero_contrato']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['fecha_contrato']}</td><td>$" . number_format($row['deposito'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'deposito_promedio':
                $sql = "SELECT AVG(deposito) as promedio FROM tbContratos";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " WHERE fecha_contrato BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $promedio = $db->query($sql)->fetchColumn() ?: 0;
                $html .= "<h2>Depósito promedio en contratos</h2>";
                $html .= "<p><strong>Promedio:</strong> $" . number_format($promedio, 2) . "</p>";
                break;

            // ==================== 5. Devoluciones ====================
            case 'devoluciones_por_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbDevoluciones GROUP BY estado")->fetchAll();
                $html .= '<h2>Devoluciones por estado</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'devoluciones_tarde':
                $sql = "SELECT d.*, r.fecha_entrega, c.nombre, c.apellido, v.marca, v.modelo
                        FROM tbDevoluciones d
                        INNER JOIN tbReservas r ON d.id_reserva = r.id_reserva
                        INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE d.fecha_devolucion_real > r.fecha_entrega";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND d.fecha_devolucion_real BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Devoluciones tardías</h2><table border="1" cellpadding="5"><tr><th>Cliente</th><th>Vehículo</th><th>Fecha pactada</th><th>Fecha real</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['marca']} {$row['modelo']}</td><td>{$row['fecha_entrega']}</td><td>{$row['fecha_devolucion_real']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'km_promedio_devolucion':
                $sql = "SELECT AVG(km_final) as promedio FROM tbDevoluciones";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " WHERE fecha_devolucion_real BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $promedio = $db->query($sql)->fetchColumn() ?: 0;
                $html .= "<h2>Kilometraje promedio al devolver</h2><p><strong>Promedio:</strong> " . round($promedio, 2) . " km</p>";
                break;

            case 'combustible_mas_comun':
                $sql = "SELECT combustible, COUNT(*) as total
                        FROM tbDevoluciones
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND fecha_devolucion_real BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY combustible ORDER BY total DESC LIMIT 1";
                $row = $db->query($sql)->fetch();
                if ($row) {
                    $html .= "<h2>Nivel de combustible más común en devoluciones</h2>";
                    $html .= "<p><strong>{$row['combustible']}</strong> ({$row['total']} veces)</p>";
                } else {
                    $html .= "<p>No hay datos</p>";
                }
                break;

            case 'devoluciones_por_vehiculo':
                $sql = "SELECT v.marca, v.modelo, v.numero_placa, COUNT(d.id_devolucion) as total
                        FROM tbDevoluciones d
                        INNER JOIN tbReservas r ON d.id_reserva = r.id_reserva
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND d.fecha_devolucion_real BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_vehiculo ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Devoluciones por vehículo</h2><table border="1" cellpadding="5"><tr><th>Vehículo</th><th>Placa</th><th>Devoluciones</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 6. Multas ====================
            case 'multas_por_tipo':
                $sql = "SELECT tipo, COUNT(*) as cantidad, SUM(monto) as total
                        FROM tbMultas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY tipo";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Multas por tipo</h2><table border="1" cellpadding="5"><tr><th>Tipo</th><th>Cantidad</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['tipo']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'multas_pendientes':
                $data = $db->query("
                    SELECT m.*, c.nombre, c.apellido, v.marca, v.modelo
                    FROM tbMultas m
                    INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                    INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                    INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                    WHERE m.pagada = 0
                ")->fetchAll();
                $html .= '<h2>Multas pendientes</h2><table border="1" cellpadding="5"><tr><th>Cliente</th><th>Vehículo</th><th>Motivo</th><th>Monto</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['marca']} {$row['modelo']}</td><td>{$row['motivo']}</td><td>$" . number_format($row['monto'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'multas_por_cliente':
                $sql = "SELECT c.id_cliente, c.nombre, c.apellido, COUNT(m.id_multa) as cantidad, SUM(m.monto) as total
                        FROM tbMultas m
                        INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                        INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND m.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY c.id_cliente ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Multas por cliente</h2><table border="1" cellpadding="5"><tr><th>Cliente</th><th>Cantidad</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'multas_por_vehiculo':
                $sql = "SELECT v.marca, v.modelo, v.numero_placa, COUNT(m.id_multa) as cantidad, SUM(m.monto) as total
                        FROM tbMultas m
                        INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND m.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_vehiculo ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Multas por vehículo</h2><table border="1" cellpadding="5"><tr><th>Vehículo</th><th>Placa</th><th>Cantidad</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'motivos_multa_mas_comunes':
                $sql = "SELECT motivo, COUNT(*) as total
                        FROM tbMultas
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY motivo ORDER BY total DESC LIMIT 10";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Motivos de multa más comunes</h2><table border="1" cellpadding="5"><tr><th>Motivo</th><th>Frecuencia</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['motivo']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 7. Checklist ====================
            case 'items_mas_fallados':
                $sql = "SELECT ci.nombre, COUNT(ci.id_item) as fallas
                        FROM tbChecklistInspeccion ci
                        INNER JOIN tbChecklistItems i ON ci.id_item = i.id_item
                        WHERE ci.estado = 'falla'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND ci.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY ci.id_item ORDER BY fallas DESC LIMIT 10";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Items más fallados</h2><table border="1" cellpadding="5"><tr><th>Item</th><th>Fallas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']}</td><td>{$row['fallas']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'fallas_por_vehiculo':
                $sql = "SELECT v.marca, v.modelo, v.numero_placa, COUNT(ci.id_check) as fallas
                        FROM tbChecklistInspeccion ci
                        INNER JOIN tbDevoluciones d ON ci.id_devolucion = d.id_devolucion
                        INNER JOIN tbReservas r ON d.id_reserva = r.id_reserva
                        INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                        WHERE ci.estado = 'falla'";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND ci.created_at BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY r.id_vehiculo ORDER BY fallas DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Fallas de checklist por vehículo</h2><table border="1" cellpadding="5"><tr><th>Vehículo</th><th>Placa</th><th>Fallas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td><td>{$row['fallas']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'inspecciones_por_inspector':
                $sql = "SELECT m.inspector, COUNT(m.id_check_meta) as inspecciones
                        FROM tbChecklistInspeccionMeta m
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND m.fecha_inspeccion BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY m.inspector ORDER BY inspecciones DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Inspecciones por inspector</h2><table border="1" cellpadding="5"><tr><th>Inspector</th><th>Inspecciones</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['inspector']}</td><td>{$row['inspecciones']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 8. Mantenimientos ====================
            case 'mantenimientos_por_tipo':
                $sql = "SELECT tipo, COUNT(*) as cantidad, SUM(costo) as total
                        FROM tb_mantenimientos
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY tipo";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Mantenimientos por tipo</h2><table border="1" cellpadding="5"><tr><th>Tipo</th><th>Cantidad</th><th>Costo total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['tipo']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'costo_mantenimiento_por_vehiculo':
                $sql = "SELECT v.marca, v.modelo, v.numero_placa, COUNT(m.id_mantenimiento) as cantidad, SUM(m.costo) as total
                        FROM tb_mantenimientos m
                        INNER JOIN tbVehiculos v ON m.id_vehiculo = v.id_vehiculo
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND m.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY m.id_vehiculo ORDER BY total DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Costo de mantenimiento por vehículo</h2><table border="1" cellpadding="5"><tr><th>Vehículo</th><th>Placa</th><th>Cantidad</th><th>Costo total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td><td>{$row['cantidad']}</td><td>$" . number_format($row['total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 9. Vehículos ====================
            case 'vehiculos_por_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbVehiculos GROUP BY estado")->fetchAll();
                $html .= '<h2>Vehículos por estado</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'vehiculos_sin_reservas':
                $data = $db->query("
                    SELECT v.* FROM tbVehiculos v
                    LEFT JOIN tbReservas r ON v.id_vehiculo = r.id_vehiculo
                    WHERE r.id_reserva IS NULL
                ")->fetchAll();
                $html .= '<h2>Vehículos sin reservas</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Vehículo</th><th>Placa</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_vehiculo']}</td><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 10. Clientes ====================
            case 'clientes_nuevos':
                $sql = "SELECT id_cliente, nombre, apellido, correo, created_at
                        FROM tbClientes
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND DATE(created_at) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " ORDER BY created_at DESC";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Clientes nuevos</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_cliente']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['correo']}</td><td>{$row['created_at']}</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'clientes_mas_reservas':
                $sql = "SELECT c.id_cliente, c.nombre, c.apellido, c.correo, COUNT(r.id_reserva) as total
                        FROM tbClientes c
                        LEFT JOIN tbReservas r ON c.id_cliente = r.id_cliente
                        WHERE 1=1";
                if ($fecha_inicio && $fecha_fin) {
                    $sql .= " AND r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
                }
                $sql .= " GROUP BY c.id_cliente ORDER BY total DESC LIMIT 20";
                $data = $db->query($sql)->fetchAll();
                $html .= '<h2>Clientes con más reservas</h2><table border="1" cellpadding="5"><tr><th>Cliente</th><th>Email</th><th>Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['nombre']} {$row['apellido']}</td><td>{$row['correo']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 11. Usuarios (administradores) ====================
            case 'usuarios_por_rol':
                $data = $db->query("
                    SELECT r.nombre as rol, COUNT(u.id_usuario) as total
                    FROM tbUsuarios u
                    RIGHT JOIN tbRoles r ON u.id_rol = r.id_rol
                    GROUP BY r.id_rol
                ")->fetchAll();
                $html .= '<h2>Usuarios por rol</h2><table border="1" cellpadding="5"><tr><th>Rol</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['rol']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 12. Asistencia ====================
            case 'asistencia_por_usuario':
                // Si no existe tabla, se puede omitir o crear un placeholder
                $html .= '<p>Módulo de asistencia no implementado.</p>';
                break;

            // ==================== 13. Notificaciones ====================
            case 'notificaciones_por_estado':
                $data = $db->query("SELECT estado, COUNT(*) as total FROM tbNotificacionesEmail GROUP BY estado")->fetchAll();
                $html .= '<h2>Notificaciones por estado</h2><table border="1" cellpadding="5"><tr><th>Estado</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['estado']}</td><td>{$row['total']}</td></tr>";
                }
                $html .= '</table>';
                break;

            // ==================== 14. Alertas / Integridad ====================
            case 'reservas_sin_pago':
                $data = $db->query("
                    SELECT r.id_reserva, c.nombre, c.apellido, r.precio_total
                    FROM tbReservas r
                    INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                    LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                    WHERE p.id_pago IS NULL
                ")->fetchAll();
                $html .= '<h2>Reservas sin pago registrado</h2><table border="1" cellpadding="5"><tr><th>Reserva</th><th>Cliente</th><th>Monto</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_reserva']}</td><td>{$row['nombre']} {$row['apellido']}</td><td>$" . number_format($row['precio_total'], 2) . "</td></tr>";
                }
                $html .= '</table>';
                break;

            case 'vehiculos_mantenimiento_sin_registro':
                $data = $db->query("
                    SELECT v.id_vehiculo, v.marca, v.modelo, v.numero_placa
                    FROM tbVehiculos v
                    LEFT JOIN tb_mantenimientos m ON v.id_vehiculo = m.id_vehiculo AND m.estado IN ('programado', 'en_proceso')
                    WHERE v.estado = 'mantenimiento' AND m.id_mantenimiento IS NULL
                ")->fetchAll();
                $html .= '<h2>Vehículos en mantenimiento sin registro activo</h2><table border="1" cellpadding="5"><tr><th>ID</th><th>Vehículo</th><th>Placa</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr><td>{$row['id_vehiculo']}</td><td>{$row['marca']} {$row['modelo']}</td><td>{$row['numero_placa']}</td></tr>";
                }
                $html .= '</table>';
                break;

            default:
                $html .= '<p>Tipo de reporte no válido.</p>';
        }

        return $html;
    }

    // ============================================
    // Métodos de exportación existentes
    // ============================================
    public function exportarExcel()
    {
        parent::__construct();
        $tipo = $_GET['tipo'] ?? 'vehiculos_mas_rentados';
        $db = Database::connect();

        $filename = 'reporte_' . $tipo . '_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        switch ($tipo) {
            case 'vehiculos_mas_rentados':
                fputcsv($output, ['Marca', 'Modelo', 'Placa', 'Total Reservas']);
                $data = $db->query("
                    SELECT v.marca, v.modelo, v.numero_placa, COUNT(r.id_reserva) as total
                    FROM tbReservas r
                    INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                    GROUP BY r.id_vehiculo
                    ORDER BY total DESC
                    LIMIT 100
                ")->fetchAll();
                foreach ($data as $row) {
                    fputcsv($output, [$row['marca'], $row['modelo'], $row['numero_placa'], $row['total']]);
                }
                break;
            case 'ingresos_mensuales':
                fputcsv($output, ['Mes', 'Total Ingresos']);
                $data = $db->query("
                    SELECT DATE_FORMAT(p.created_at, '%Y-%m') as mes, SUM(p.monto) as total
                    FROM tbPagos p
                    WHERE p.estado = 'pagado'
                    GROUP BY mes
                    ORDER BY mes DESC
                ")->fetchAll();
                foreach ($data as $row) {
                    fputcsv($output, [$row['mes'], $row['total']]);
                }
                break;
            case 'top_clientes':
                fputcsv($output, ['Nombre', 'Email', 'Total Reservas']);
                $data = $db->query("
                    SELECT CONCAT(c.nombre, ' ', c.apellido) as nombre, c.correo, COUNT(r.id_reserva) as total
                    FROM tbReservas r
                    INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                    GROUP BY r.id_cliente
                    ORDER BY total DESC
                    LIMIT 100
                ")->fetchAll();
                foreach ($data as $row) {
                    fputcsv($output, [$row['nombre'], $row['correo'], $row['total']]);
                }
                break;
        }
        fclose($output);
        exit;
    }

    public function exportarPdf()
    {
        parent::__construct();
        $tipo = $_GET['tipo'] ?? 'vehiculos_mas_rentados';
        require_once __DIR__ . '/../../vendor/autoload.php';

        $db = Database::connect();
        $mpdf = new \Mpdf\Mpdf();

        switch ($tipo) {
            case 'vehiculos_mas_rentados':
                $data = $db->query("
                    SELECT v.marca, v.modelo, v.numero_placa, COUNT(r.id_reserva) as total
                    FROM tbReservas r
                    INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                    GROUP BY r.id_vehiculo
                    ORDER BY total DESC
                    LIMIT 50
                ")->fetchAll();

                $html = '<h1>Vehículos más rentados</h1>';
                $html .= '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                $html .= '<tr><th>Marca</th><th>Modelo</th><th>Placa</th><th>Total Reservas</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr>
                        <td>{$row['marca']}</td>
                        <td>{$row['modelo']}</td>
                        <td>{$row['numero_placa']}</td>
                        <td style='text-align:center'>{$row['total']}</td>
                    </tr>";
                }
                $html .= '</table>';
                $mpdf->WriteHTML($html);
                $mpdf->Output('vehiculos_mas_rentados.pdf', 'D');
                break;
            case 'ingresos_mensuales':
                $data = $db->query("
                    SELECT DATE_FORMAT(p.created_at, '%Y-%m') as mes, SUM(p.monto) as total
                    FROM tbPagos p
                    WHERE p.estado = 'pagado'
                    GROUP BY mes
                    ORDER BY mes DESC
                    LIMIT 12
                ")->fetchAll();

                $html = '<h1>Ingresos mensuales</h1>';
                $html .= '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
                $html .= '<tr><th>Mes</th><th>Total</th></tr>';
                foreach ($data as $row) {
                    $html .= "<tr>
                        <td>{$row['mes']}</td>
                        <td style='text-align:right'>$" . number_format($row['total'], 2) . "</td>
                    </tr>";
                }
                $html .= '</table>';
                $mpdf->WriteHTML($html);
                $mpdf->Output('ingresos_mensuales.pdf', 'D');
                break;
        }
        exit;
    }
}