<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';

class DashboardController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();

        $db = Database::connect();

        // Estadísticas generales
        $totalClientes = $db->query("SELECT COUNT(*) FROM tbClientes")->fetchColumn();
        $vehiculosDisponibles = $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'disponible'")->fetchColumn();
        $reservasActivas = $db->query("SELECT COUNT(*) FROM tbReservas WHERE estado = 'en_curso'")->fetchColumn();
        $devolucionesHoy = $db->query("SELECT COUNT(*) FROM tbDevoluciones WHERE DATE(fecha_devolucion_real) = CURDATE()")->fetchColumn();

        // Próximas devoluciones (reservas en curso con fecha de entrega en los próximos 7 días)
        $proximasDevoluciones = $db->query("
            SELECT r.id_reserva, r.fecha_entrega, 
                   c.nombre, c.apellido, 
                   v.marca, v.modelo, v.numero_placa,
                   ct.numero_contrato
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN tbContratos ct ON r.id_reserva = ct.id_reserva
            WHERE r.estado = 'en_curso' 
              AND r.fecha_entrega BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ORDER BY r.fecha_entrega ASC
            LIMIT 5
        ")->fetchAll();

        // Reservas recientes (últimas 5)
        $reservasRecientes = $db->query("
            SELECT r.id_reserva, r.fecha_reserva, r.fecha_recogida, r.fecha_entrega,
                   c.nombre, c.apellido,
                   v.marca, v.modelo, v.numero_placa,
                   ct.numero_contrato
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            LEFT JOIN tbContratos ct ON r.id_reserva = ct.id_reserva
            ORDER BY r.fecha_reserva DESC
            LIMIT 5
        ")->fetchAll();

        // Datos para gráfico (reservas por mes últimos 6 meses)
        $reservasPorMes = $db->query("
            SELECT DATE_FORMAT(fecha_reserva, '%Y-%m') as mes, COUNT(*) as total
            FROM tbReservas
            WHERE fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY mes
            ORDER BY mes
        ")->fetchAll();

        $meses = [];
        $totales = [];
        foreach ($reservasPorMes as $r) {
            $meses[] = $r['mes'];
            $totales[] = $r['total'];
        }

        // Pasar variables a la vista
        require PROJECT_ROOT_FS . '/views/admin/dashboard/index.php';
    }
}