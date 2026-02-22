<?php

class Notificacion {

    private $db;
    private $table = "Notificaciones";

    public function __construct($db) {
        $this->db = $db;
    }

    // Obtener reservas cuya entrega es mañana
    public function obtenerReservasProximas() {

        $sql = "SELECT r.id_reserva, r.fecha_entrega,
                       c.nombre, c.apellido, c.correo
                FROM Reservas r
                JOIN Clientes c ON r.id_cliente = c.id_cliente
                WHERE r.estado = 'activa'
                AND r.fecha_entrega = CURDATE() + INTERVAL 1 DAY";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar notificación en la base
    public function registrarNotificacion($id_reserva, $destinatario, $asunto, $mensaje_resumen, $estado, $error = null) {

        $sql = "INSERT INTO {$this->table}
                (id_reserva, destinatario, asunto, mensaje_resumen, estado, fecha_envio, error_msg, created_at)
                VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $id_reserva,
            $destinatario,
            $asunto,
            $mensaje_resumen,
            $estado,
            $error
        ]);
    }
}
