<?php
// controller/Admin/ReportesAvanzadosController.php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class ReportesAvanzadosController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $titulo = 'Reportes Avanzados';
        $seccion = 'reportes';
        require PROJECT_ROOT_FS . '/views/admin/reportes/avanzados/index.php';
    }

    public function generar()
    {
        parent::__construct();
        $tipo = $_GET['tipo'] ?? 'reservas';
        $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
        $formato = $_GET['formato'] ?? 'html';

        $db = Database::connect();
        $datos = [];
        $tituloReporte = '';

        switch ($tipo) {
            case 'reservas':
                $tituloReporte = 'Reservas por rango de fechas';
                $datos = $this->reporteReservas($db, $fecha_inicio, $fecha_fin);
                break;
            case 'ingresos_concepto':
                $tituloReporte = 'Ingresos por concepto';
                $datos = $this->reporteIngresosConcepto($db, $fecha_inicio, $fecha_fin);
                break;
            case 'top_vehiculos':
                $tituloReporte = 'Top vehículos más rentados';
                $datos = $this->reporteTopVehiculos($db, $fecha_inicio, $fecha_fin);
                break;
            case 'top_clientes':
                $tituloReporte = 'Top clientes por gasto';
                $datos = $this->reporteTopClientes($db, $fecha_inicio, $fecha_fin);
                break;
            case 'multas':
                $tituloReporte = 'Multas pendientes y pagadas';
                $datos = $this->reporteMultas($db, $fecha_inicio, $fecha_fin);
                break;
            case 'checklist_fallas':
                $tituloReporte = 'Incidencias de checklist (fallas)';
                $datos = $this->reporteChecklistFallas($db, $fecha_inicio, $fecha_fin);
                break;
            case 'mantenimientos':
                $tituloReporte = 'Mantenimientos por vehículo';
                $datos = $this->reporteMantenimientos($db, $fecha_inicio, $fecha_fin);
                break;
            case 'devoluciones_tardias':
                $tituloReporte = 'Devoluciones tardías';
                $datos = $this->reporteDevolucionesTardias($db, $fecha_inicio, $fecha_fin);
                break;
            case 'utilizacion_flota':
                $tituloReporte = 'Utilización de flota por día';
                $datos = $this->reporteUtilizacionFlota($db, $fecha_inicio, $fecha_fin);
                break;
            default:
                die('Tipo de reporte no válido');
        }

        if ($formato === 'pdf') {
            $this->generarPDF($tituloReporte, $datos, $tipo, $fecha_inicio, $fecha_fin);
        } else {
            // Mostrar resultados en HTML
            $resultados = $datos;
            require PROJECT_ROOT_FS . '/views/admin/reportes/avanzados/resultados.php';
        }
    }

    // ================= CONSULTAS ESPECÍFICAS =================

    private function reporteReservas($db, $inicio, $fin)
    {
        $sql = "SELECT r.id_reserva, r.fecha_reserva, r.fecha_recogida, r.fecha_entrega, r.precio_total, r.estado,
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido, c.correo,
                       v.marca, v.modelo, v.numero_placa
                FROM tbReservas r
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE DATE(r.fecha_reserva) BETWEEN :inicio AND :fin
                ORDER BY r.fecha_reserva DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteIngresosConcepto($db, $inicio, $fin)
    {
        $sql = "SELECT pd.concepto, COUNT(*) as cantidad, SUM(pd.monto) as total
                FROM tb_pago_detalle pd
                INNER JOIN tbPagos p ON pd.id_pago = p.id_pago
                WHERE p.estado = 'pagado' AND DATE(p.created_at) BETWEEN :inicio AND :fin
                GROUP BY pd.concepto
                ORDER BY total DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteTopVehiculos($db, $inicio, $fin)
    {
        $sql = "SELECT v.id_vehiculo, v.marca, v.modelo, v.numero_placa, v.tipo_vehiculo,
                       COUNT(r.id_reserva) as total_reservas,
                       COALESCE(SUM(p.monto), 0) as total_ingresos
                FROM tbVehiculos v
                LEFT JOIN tbReservas r ON v.id_vehiculo = r.id_vehiculo AND DATE(r.fecha_reserva) BETWEEN :inicio AND :fin
                LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                GROUP BY v.id_vehiculo
                ORDER BY total_reservas DESC, total_ingresos DESC
                LIMIT 20";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteTopClientes($db, $inicio, $fin)
    {
        $sql = "SELECT c.id_cliente, c.nombre, c.apellido, c.correo,
                       COUNT(r.id_reserva) as total_reservas,
                       COALESCE(SUM(p.monto), 0) as total_gastado
                FROM tbClientes c
                LEFT JOIN tbReservas r ON c.id_cliente = r.id_cliente AND DATE(r.fecha_reserva) BETWEEN :inicio AND :fin
                LEFT JOIN tbPagos p ON r.id_reserva = p.id_reserva AND p.estado = 'pagado'
                GROUP BY c.id_cliente
                ORDER BY total_gastado DESC
                LIMIT 20";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteMultas($db, $inicio, $fin)
    {
        $sql = "SELECT m.id_multa, m.tipo, m.monto, m.motivo, m.pagada, m.created_at,
                       c.nombre, c.apellido, v.marca, v.modelo, v.numero_placa
                FROM tbMultas m
                INNER JOIN tbReservas r ON m.id_reserva = r.id_reserva
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE DATE(m.created_at) BETWEEN :inicio AND :fin
                ORDER BY m.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteChecklistFallas($db, $inicio, $fin)
    {
        // Ítems más fallados
        $sql = "SELECT ci.nombre, COUNT(ci.id_item) as total_fallas
                FROM tbChecklistInspeccion ci
                INNER JOIN tbDevoluciones d ON ci.id_devolucion = d.id_devolucion
                WHERE ci.estado = 'falla' AND DATE(d.created_at) BETWEEN :inicio AND :fin
                GROUP BY ci.id_item
                ORDER BY total_fallas DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteMantenimientos($db, $inicio, $fin)
    {
        $sql = "SELECT m.id_mantenimiento, m.tipo, m.descripcion, m.fecha_inicio, m.fecha_fin, m.costo, m.km_actual, m.estado,
                       v.marca, v.modelo, v.numero_placa
                FROM tb_mantenimientos m
                INNER JOIN tbVehiculos v ON m.id_vehiculo = v.id_vehiculo
                WHERE DATE(m.fecha_inicio) BETWEEN :inicio AND :fin
                ORDER BY m.fecha_inicio DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteDevolucionesTardias($db, $inicio, $fin)
    {
        $sql = "SELECT d.id_devolucion, d.fecha_devolucion_real, r.fecha_entrega, 
                       DATEDIFF(d.fecha_devolucion_real, r.fecha_entrega) as dias_atraso,
                       c.nombre, c.apellido, v.marca, v.modelo, v.numero_placa
                FROM tbDevoluciones d
                INNER JOIN tbReservas r ON d.id_reserva = r.id_reserva
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                WHERE d.fecha_devolucion_real > r.fecha_entrega
                  AND DATE(d.fecha_devolucion_real) BETWEEN :inicio AND :fin
                ORDER BY dias_atraso DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['inicio' => $inicio, 'fin' => $fin]);
        return $stmt->fetchAll();
    }

    private function reporteUtilizacionFlota($db, $inicio, $fin)
    {
        // Para cada día en el rango, calcular cuántos vehículos estaban rentados
        $fecha_actual = strtotime($inicio);
        $fecha_fin = strtotime($fin);
        $resultados = [];
        $totalVehiculos = $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();

        while ($fecha_actual <= $fecha_fin) {
            $dia = date('Y-m-d', $fecha_actual);
            $rentados = $db->prepare("
                SELECT COUNT(DISTINCT id_vehiculo) FROM tbReservas
                WHERE estado IN ('en_curso', 'completada')
                  AND fecha_recogida <= ? AND fecha_entrega >= ?
            ");
            $rentados->execute([$dia, $dia]);
            $cantidad = $rentados->fetchColumn();
            $porcentaje = $totalVehiculos > 0 ? round(($cantidad / $totalVehiculos) * 100, 2) : 0;
            $resultados[] = [
                'fecha' => $dia,
                'rentados' => $cantidad,
                'total' => $totalVehiculos,
                'porcentaje' => $porcentaje
            ];
            $fecha_actual = strtotime('+1 day', $fecha_actual);
        }
        return $resultados;
    }

    // ================= GENERACIÓN DE PDF =================

    private function generarPDF($titulo, $datos, $tipo, $inicio, $fin)
    {
        $mpdf = new \Mpdf\Mpdf();
        $html = '<h1>' . $titulo . '</h1>';
        $html .= '<p><strong>Período:</strong> ' . date('d/m/Y', strtotime($inicio)) . ' al ' . date('d/m/Y', strtotime($fin)) . '</p>';
        $html .= '<hr>';

        // Generar tabla según el tipo de reporte
        $html .= $this->generarTablaPDF($datos, $tipo);

        $mpdf->WriteHTML($html);
        $mpdf->Output($titulo . '.pdf', 'D'); // Descargar
        exit;
    }

    private function generarTablaPDF($datos, $tipo)
    {
        if (empty($datos)) {
            return '<p>No hay datos para mostrar.</p>';
        }

        $html = '<table border="1" cellpadding="5" style="border-collapse:collapse; width:100%;">';
        $html .= '<thead><tr>';

        // Cabeceras según tipo
        switch ($tipo) {
            case 'reservas':
                $html .= '<th>ID</th><th>Fecha</th><th>Cliente</th><th>Vehículo</th><th>Recogida</th><th>Entrega</th><th>Total</th><th>Estado</th>';
                break;
            case 'ingresos_concepto':
                $html .= '<th>Concepto</th><th>Cantidad</th><th>Total</th>';
                break;
            case 'top_vehiculos':
                $html .= '<th>Vehículo</th><th>Placa</th><th>Tipo</th><th>Reservas</th><th>Ingresos</th>';
                break;
            case 'top_clientes':
                $html .= '<th>Cliente</th><th>Email</th><th>Reservas</th><th>Gasto</th>';
                break;
            case 'multas':
                $html .= '<th>ID</th><th>Fecha</th><th>Cliente</th><th>Vehículo</th><th>Tipo</th><th>Motivo</th><th>Monto</th><th>Pagada</th>';
                break;
            case 'checklist_fallas':
                $html .= '<th>Ítem</th><th>Total fallas</th>';
                break;
            case 'mantenimientos':
                $html .= '<th>Vehículo</th><th>Tipo</th><th>Descripción</th><th>Inicio</th><th>Fin</th><th>Costo</th><th>KM</th><th>Estado</th>';
                break;
            case 'devoluciones_tardias':
                $html .= '<th>Cliente</th><th>Vehículo</th><th>Fecha programada</th><th>Fecha real</th><th>Días atraso</th>';
                break;
            case 'utilizacion_flota':
                $html .= '<th>Fecha</th><th>Vehículos rentados</th><th>Total flota</th><th>% Ocupación</th>';
                break;
        }
        $html .= '</tr></thead><tbody>';

        foreach ($datos as $row) {
            $html .= '<tr>';
            switch ($tipo) {
                case 'reservas':
                    $html .= '<td>' . $row['id_reserva'] . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_reserva'])) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['cliente_nombre'] . ' ' . $row['cliente_apellido']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_recogida'])) . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_entrega'])) . '</td>';
                    $html .= '<td>$' . number_format($row['precio_total'], 2) . '</td>';
                    $html .= '<td>' . ucfirst($row['estado']) . '</td>';
                    break;
                case 'ingresos_concepto':
                    $html .= '<td>' . ucfirst($row['concepto']) . '</td>';
                    $html .= '<td>' . $row['cantidad'] . '</td>';
                    $html .= '<td>$' . number_format($row['total'], 2) . '</td>';
                    break;
                case 'top_vehiculos':
                    $html .= '<td>' . htmlspecialchars($row['marca'] . ' ' . $row['modelo']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['numero_placa']) . '</td>';
                    $html .= '<td>' . $row['tipo_vehiculo'] . '</td>';
                    $html .= '<td>' . $row['total_reservas'] . '</td>';
                    $html .= '<td>$' . number_format($row['total_ingresos'], 2) . '</td>';
                    break;
                case 'top_clientes':
                    $html .= '<td>' . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['correo']) . '</td>';
                    $html .= '<td>' . $row['total_reservas'] . '</td>';
                    $html .= '<td>$' . number_format($row['total_gastado'], 2) . '</td>';
                    break;
                case 'multas':
                    $html .= '<td>' . $row['id_multa'] . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['created_at'])) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') . '</td>';
                    $html .= '<td>' . ucfirst($row['tipo']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['motivo']) . '</td>';
                    $html .= '<td>$' . number_format($row['monto'], 2) . '</td>';
                    $html .= '<td>' . ($row['pagada'] ? 'Sí' : 'No') . '</td>';
                    break;
                case 'checklist_fallas':
                    $html .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                    $html .= '<td>' . $row['total_fallas'] . '</td>';
                    break;
                case 'mantenimientos':
                    $html .= '<td>' . htmlspecialchars($row['marca'] . ' ' . $row['modelo'] . ' (' . $row['numero_placa'] . ')') . '</td>';
                    $html .= '<td>' . ucfirst($row['tipo']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['descripcion']) . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_inicio'])) . '</td>';
                    $html .= '<td>' . ($row['fecha_fin'] ? date('d/m/Y', strtotime($row['fecha_fin'])) : '-') . '</td>';
                    $html .= '<td>$' . number_format($row['costo'], 2) . '</td>';
                    $html .= '<td>' . ($row['km_actual'] ?? '-') . '</td>';
                    $html .= '<td>' . ucfirst($row['estado']) . '</td>';
                    break;
                case 'devoluciones_tardias':
                    $html .= '<td>' . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['marca'] . ' ' . $row['modelo']) . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_entrega'])) . '</td>';
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha_devolucion_real'])) . '</td>';
                    $html .= '<td>' . $row['dias_atraso'] . ' días</td>';
                    break;
                case 'utilizacion_flota':
                    $html .= '<td>' . date('d/m/Y', strtotime($row['fecha'])) . '</td>';
                    $html .= '<td>' . $row['rentados'] . '</td>';
                    $html .= '<td>' . $row['total'] . '</td>';
                    $html .= '<td>' . $row['porcentaje'] . '%</td>';
                    break;
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }
}