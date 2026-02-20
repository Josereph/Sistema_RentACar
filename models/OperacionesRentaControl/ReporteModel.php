<?php

require_once __DIR__ . '/../../config/conexion.php';

class ReporteModel {

    private $conn;

    public function __construct() {
        global $conexion;
        $this->conn = $conexion;
    }

    public function obtenerVehiculosMasRentados() {

        $sql = "
            SELECT 
                v.car_name,
                v.marca,
                COUNT(r.id_reserva) AS veces_rentado,
                SUM(r.precio_total) AS ganancias
            FROM Reservas r
            INNER JOIN Vehiculos v 
                ON r.id_vehiculo = v.id_vehiculo
            WHERE r.estado = 'finalizada'
            GROUP BY r.id_vehiculo
            ORDER BY veces_rentado DESC
        ";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
}
