<?php

class Multa {

    private $conn;
    private $table = "Multas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nueva multa
    public function crearMulta($id_reserva, $id_devolucion, $tipo, $monto, $motivo) {

        $query = "INSERT INTO " . $this->table . "
                  (id_reserva, id_devolucion, tipo, monto, motivo, pagada, created_at)
                  VALUES (:id_reserva, :id_devolucion, :tipo, :monto, :motivo, 0, NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_reserva', $id_reserva);
        $stmt->bindParam(':id_devolucion', $id_devolucion);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':motivo', $motivo);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

}
