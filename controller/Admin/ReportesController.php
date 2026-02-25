<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';

class ReportesController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $db = Database::connect();

        // 1. Vehículos más rentados (últimos 12 meses)
        $vehiculosMasRentados = $db->query("
            SELECT v.marca, v.modelo, v.numero_placa, COUNT(r.id_reserva) as total_reservas
            FROM tbReservas r
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE r.fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY r.id_vehiculo
            ORDER BY total_reservas DESC
            LIMIT 10
        ")->fetchAll();

        // 2. Ingresos por mes (últimos 12 meses)
        $ingresosPorMes = $db->query("
            SELECT DATE_FORMAT(p.created_at, '%Y-%m') as mes, SUM(p.monto) as total
            FROM tbPagos p
            WHERE p.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY mes
            ORDER BY mes
        ")->fetchAll();

        // 3. Clientes con más reservas
        $topClientes = $db->query("
            SELECT c.nombre, c.apellido, c.correo, COUNT(r.id_reserva) as total_reservas
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            GROUP BY r.id_cliente
            ORDER BY total_reservas DESC
            LIMIT 10
        ")->fetchAll();

        // 4. Multas: total pendientes y pagadas
        $multasPendientes = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbMultas WHERE pagada = 0")->fetchColumn();
        $multasPagadas = $db->query("SELECT COALESCE(SUM(monto), 0) FROM tbMultas WHERE pagada = 1")->fetchColumn();

        // 5. Ocupación promedio (último mes)
        $ocupacion = $db->query("
            SELECT AVG(ocupacion) as promedio FROM (
                SELECT DATE(fecha_recogida) as dia, COUNT(*) as ocupacion
                FROM tbReservas
                WHERE fecha_recogida >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                GROUP BY dia
            ) t
        ")->fetchColumn() ?: 0;
        $totalVehiculos = $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();
        $porcentajeOcupacion = $totalVehiculos > 0 ? round(($ocupacion / $totalVehiculos) * 100, 2) : 0;

        $titulo = 'Reportes Estadísticos';
        $seccion = 'reportes';
        require PROJECT_ROOT_FS . '/views/admin/reportes/index.php';
    }

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
                    SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, SUM(monto) as total
                    FROM tbPagos
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
                    SELECT DATE_FORMAT(created_at, '%Y-%m') as mes, SUM(monto) as total
                    FROM tbPagos
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