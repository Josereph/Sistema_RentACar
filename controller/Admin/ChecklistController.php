<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ChecklistItem.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ChecklistInspeccion.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ChecklistInspeccionMeta.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Devolucion.php';
require_once __DIR__ . '/../../config/db.php'; // Para la consulta adicional
require_once __DIR__ . '/../../helpers/EmailHelper.php'; // <-- AÑADIDO

class ChecklistController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $id_devolucion = isset($_GET['id_devolucion']) ? (int)$_GET['id_devolucion'] : 0;
        if ($id_devolucion <= 0) {
            die("Falta id_devolucion en la URL.");
        }

        $devolucion = Devolucion::getById($id_devolucion);
        if (!$devolucion) {
            die("No existe la devolución con id_devolucion={$id_devolucion}");
        }

        $items     = ChecklistItem::allActive();
        $checks    = ChecklistInspeccion::getByDevolucion($id_devolucion);
        $meta      = ChecklistInspeccionMeta::getByDevolucion($id_devolucion);

        require PROJECT_ROOT_FS . '/views/admin/checklist/index.php';
    }

    public function guardar()
    {
        parent::__construct();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Método no permitido");
        }

        $id_devolucion = isset($_POST['id_devolucion']) ? (int)$_POST['id_devolucion'] : 0;
        if ($id_devolucion <= 0) {
            die("Falta id_devolucion");
        }

        $devolucion = Devolucion::getById($id_devolucion);
        if (!$devolucion) {
            die("No existe la devolución con id_devolucion={$id_devolucion}");
        }

        // Guardar META
        $inspector = trim((string)($_POST['inspector'] ?? ''));
        $fecha_inspeccion = (string)($_POST['fecha_inspeccion'] ?? '');
        $km_inspeccion = (int)($_POST['km_inspeccion'] ?? 0);
        $nivel_combustible = (string)($_POST['nivel_combustible'] ?? 'medio');
        $observaciones_generales = trim((string)($_POST['observaciones_generales'] ?? ''));
        $firma_inspector = (string)($_POST['firma_inspector'] ?? '');

        if ($inspector === '' || $fecha_inspeccion === '' || $km_inspeccion <= 0) {
            die("Faltan datos de inspección (inspector/fecha/km).");
        }

        if ($observaciones_generales === '') $observaciones_generales = null;
        if ($firma_inspector === '') $firma_inspector = null;

        $fecha_sql = str_replace('T', ' ', $fecha_inspeccion);
        if (strlen($fecha_sql) === 16) {
            $fecha_sql .= ':00';
        }

        ChecklistInspeccionMeta::upsert(
            $id_devolucion,
            $inspector,
            $fecha_sql,
            $km_inspeccion,
            $nivel_combustible,
            $observaciones_generales,
            $firma_inspector
        );

        // Guardar ITEMS
        $items = ChecklistItem::allActive();
        $postItems = $_POST['items'] ?? [];

        foreach ($items as $it) {
            $id_item = (int)$it['id_item'];

            $estado = $postItems[$id_item]['estado'] ?? 'ok';
            $estado = ($estado === 'falla') ? 'falla' : 'ok';

            $nota = $postItems[$id_item]['nota'] ?? null;
            $nota = is_string($nota) ? trim($nota) : null;
            if ($nota === '') $nota = null;

            ChecklistInspeccion::upsert($id_devolucion, $id_item, $estado, $nota);
        }

        // Enviar correo de checklist completado
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT c.correo, c.nombre, v.marca, v.modelo, v.numero_placa
            FROM tbDevoluciones d
            INNER JOIN tbReservas r ON d.id_reserva = r.id_reserva
            INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
            INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
            WHERE d.id_devolucion = ?
        ");
        $stmt->execute([$id_devolucion]);
        $datos = $stmt->fetch();
        if ($datos) {
            EmailHelper::sendChecklistCompletado($datos['correo'], $datos['nombre'], [
                'vehiculo' => $datos['marca'] . ' ' . $datos['modelo'] . ' (' . $datos['numero_placa'] . ')'
            ]);
        }

        header("Location: /Sistema_RentACar/index.php?controller=Devolucion&action=listado&success=checklist_completado");
        exit;
    }
}