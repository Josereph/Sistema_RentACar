<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Multa.php';

class MultasController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $multasPendientes = Multa::all(false);
        $multasPagadas = Multa::all(true);
        $titulo = 'Gestión de Multas';
        $seccion = 'multas';
        require PROJECT_ROOT_FS . '/views/admin/multas/index.php';
    }

    public function pagar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            Multa::marcarPagada($id);
        }
        header('Location: ' . url('index.php?controller=Multas&action=index&success=pagada'));
        exit;
    }
}