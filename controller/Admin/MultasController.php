<?php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../models/AdministracionClientesOperaciones/Multa.php';

class MultasController extends BaseAdminController
{
    public function index()
    {
        parent::__construct();
        $multas = Multa::allPendientes();
        $titulo = 'Multas Pendientes';
        $seccion = 'multas';
        require PROJECT_ROOT_FS . '/views/admin/multas/index.php';
    }

    public function pagar()
    {
        parent::__construct();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            Multa::pagar($id);
        }
        header('Location: /Sistema_RentACar/index.php?controller=Multas&action=index');
        exit;
    }
}