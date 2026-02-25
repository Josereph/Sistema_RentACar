<?php
require_once __DIR__ . '/../../config/db.php';

class Pago
{
    public static function create($id_reserva, $monto, $metodo, $conceptos = [])
    {
        $db = Database::connect();
        // No iniciar transacción aquí, se espera que ya haya una activa desde el controlador

        // Insertar pago principal
        $sql = "INSERT INTO tbPagos (id_reserva, monto, metodo, estado, referencia) 
                VALUES (?, ?, ?, 'pagado', ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_reserva, $monto, $metodo, uniqid()]);
        $id_pago = $db->lastInsertId();

        // Insertar detalles
        foreach ($conceptos as $concepto) {
            $sql = "INSERT INTO tb_pago_detalle (id_pago, concepto, monto, descripcion) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id_pago, $concepto['concepto'], $concepto['monto'], $concepto['descripcion'] ?? null]);
        }

        return $id_pago;
    }
}