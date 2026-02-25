<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';

class ReportesController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $db = Database::connect();

        // Estadísticas generales
        $totalClientes = $db->query("SELECT COUNT(*) FROM tbClientes")->fetchColumn();
        $totalVehiculos = $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();
        $reservasMes = $db->query("SELECT COUNT(*) FROM tbReservas WHERE MONTH(fecha_reserva) = MONTH(CURDATE()) AND YEAR(fecha_reserva) = YEAR(CURDATE())")->fetchColumn();
        $ingresosMes = $db->query("SELECT SUM(monto) FROM tbPagos WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn() ?: 0;

        // Reservas por mes (para gráfica)
        $reservasPorMes = $db->query("
            SELECT DATE_FORMAT(fecha_reserva, '%Y-%m') as mes, COUNT(*) as total
            FROM tbReservas
            WHERE fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY mes
            ORDER BY mes
        ")->fetchAll();

        $meses = [];
        $totales = [];
        foreach ($reservasPorMes as $r) {
            $meses[] = $r['mes'];
            $totales[] = $r['total'];
        }

        $titulo = 'Reportes Estadísticos';
        $seccion = 'reportes';
        include __DIR__ . '/../../views/admin/reportes/index.php';
    }
}