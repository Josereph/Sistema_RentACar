<?php

class Devolucion {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function crearDevolucion($id_reserva, $fecha_real) {

        $sql = "INSERT INTO tbDevoluciones (id_reserva, fecha_real)
                VALUES (?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_reserva, $fecha_real]);

        return $this->db->lastInsertId();
    }
}
