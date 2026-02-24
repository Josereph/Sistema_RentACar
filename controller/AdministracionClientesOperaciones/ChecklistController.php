<?php
// /controller/AdministracionClientesOperaciones/ChecklistController.php

require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ChecklistItem.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/ChecklistInspeccion.php';
require_once __DIR__ . '/../../models/OperacionesRentaControl/Devolucion.php';

class ChecklistController
{
    public function index(): void
    {
        $id_devolucion = isset($_GET['id_devolucion']) ? (int)$_GET['id_devolucion'] : 0;
        if ($id_devolucion <= 0) {
            die("Falta id_devolucion en la URL.");
        }

        $devolucion = Devolucion::getById($id_devolucion);
        if (!$devolucion) {
            die("No existe la devolución con id_devolucion={$id_devolucion}");
        }

        $items = ChecklistItem::allActive();
        $existente = ChecklistInspeccion::getByDevolucion($id_devolucion);

        // variables para la vista
        require __DIR__ . '/../../views/AdministracionClientesOperaciones/checklist.php';
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Método no permitido");
        }

        $id_devolucion = isset($_POST['id_devolucion']) ? (int)$_POST['id_devolucion'] : 0;
        if ($id_devolucion <= 0) {
            die("Falta id_devolucion");
        }

        $items = ChecklistItem::allActive();

        foreach ($items as $it) {
            $id_item = (int)$it['id_item'];

            // name="estado[ID]"
            $estado = $_POST['estado'][$id_item] ?? 'ok'; // default ok
            $estado = ($estado === 'falla') ? 'falla' : 'ok';

            // name="nota[ID]"
            $nota = $_POST['nota'][$id_item] ?? null;
            $nota = is_string($nota) ? trim($nota) : null;
            if ($nota === '') $nota = null;

            ChecklistInspeccion::upsert($id_devolucion, $id_item, $estado, $nota);
        }

        header("Location: index.php?controller=Checklist&action=index&id_devolucion=" . $id_devolucion . "&saved=1");
        exit;
    }
}