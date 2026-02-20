<?php

require_once __DIR__ . '/../../models/OperacionesRentaControl/ReporteModel.php';

class ReporteController {

    private $reporteModel;

    public function __construct() {
        $this->reporteModel = new ReporteModel();
    }

    public function mostrarReporte() {

        $datos = $this->reporteModel->obtenerVehiculosMasRentados();

        require_once __DIR__ . '/../../views/OperacionesRentaControl/reporteVehiculos.php';
    }
}
