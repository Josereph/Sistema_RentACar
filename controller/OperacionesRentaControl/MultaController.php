<?php

require_once __DIR__ . '/../models/Multa.php';

class MultaController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Verificar y generar multa por retraso
    public function verificarMultaPorRetraso($id_reserva, $id_devolucion, $fecha_programada, $fecha_real) {

        $diasRetraso = $this->calcularDiasRetraso($fecha_programada, $fecha_real);

        if ($diasRetraso > 0) {

            $montoPorDia = 10; // Puedes cambiarlo si tu sistema define otra tarifa
            $montoTotal = $diasRetraso * $montoPorDia;

            $motivo = "Entrega con $diasRetraso día(s) de retraso";

            $multa = new Multa($this->db);

            return $multa->crearMulta(
                $id_reserva,
                $id_devolucion,
                'tarde',
                $montoTotal,
                $motivo
            );
        }

        return false; // No hay retraso
    }

    // Función privada para calcular días de retraso
    private function calcularDiasRetraso($fecha_programada, $fecha_real) {

        $fecha1 = new DateTime($fecha_programada);
        $fecha2 = new DateTime($fecha_real);

        if ($fecha2 <= $fecha1) {
            return 0;
        }

        $diferencia = $fecha1->diff($fecha2);

        return $diferencia->days;
    }

}
