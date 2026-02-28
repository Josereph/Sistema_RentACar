<?php
require_once __DIR__ . '/BaseEmpleadoController.php';
require_once __DIR__ . '/../../config/db.php';

class DashboardEmpleadoController extends BaseEmpleadoController
{
    public function index()
    {
        parent::__construct();
        $db = Database::connect();

        // Estadísticas básicas
        $totalClientes = $db->query("SELECT COUNT(*) FROM tbClientes")->fetchColumn();
        $vehiculosDisponibles = $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'disponible'")->fetchColumn();
        $reservasHoy = $db->query("SELECT COUNT(*) FROM tbReservas WHERE DATE(fecha_reserva) = CURDATE()")->fetchColumn();
        $devolucionesPendientes = $db->query("
            SELECT COUNT(*) FROM tbReservas 
            WHERE estado = 'en_curso' AND fecha_entrega <= DATE_ADD(CURDATE(), INTERVAL 2 DAY)
        ")->fetchColumn();

        // Reservas recientes (últimas 5)
        $reservasRecientes = $db->query("
            SELECT r.id_reserva, c.nombre, c.apellido, v.marca, v.modelo, r.fecha_recogida, r.fecha_entrega, r.estado
            FROM tbReservas r
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            ORDER BY r.fecha_reserva DESC
            LIMIT 5
        ")->fetchAll();

        $titulo = 'Panel de Empleado';
        $seccion = 'dashboard';
        require PROJECT_ROOT_FS . '/views/empleado/dashboard/index.php';
    }
}