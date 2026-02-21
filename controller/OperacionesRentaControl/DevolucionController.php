<?php

require_once __DIR__ . '/../models/Devolucion.php';
require_once __DIR__ . '/MultaController.php';

class DevolucionController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registrarDevolucion($id_reserva, $fecha_programada, $fecha_real) {

        // 1️⃣ Registrar devolución
        $devolucion = new Devolucion($this->db);

        $id_devolucion = $devolucion->crearDevolucion(
            $id_reserva,
            $fecha_real
        );

        // 2️⃣ Verificar multa por retraso
        $multaController = new MultaController($this->db);

        $multaController->verificarMultaPorRetraso(
            $id_reserva,
            $id_devolucion,
            $fecha_programada,
            $fecha_real
        );

        return $id_devolucion;
    }
}
