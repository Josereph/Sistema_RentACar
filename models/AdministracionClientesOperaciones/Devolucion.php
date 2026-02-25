<?php
require_once __DIR__ . '/../../config/db.php';

class Devolucion
{
    public static function getById(int $id_devolucion): ?array
    {
        $db = Database::connect();

        $sql = "SELECT d.*, r.id_vehiculo, v.numero_placa, v.marca, v.modelo, v.year,
                       c.nombre as cliente_nombre, c.apellido as cliente_apellido
                FROM tbDevoluciones d
                INNER JOIN tbReservas r ON r.id_reserva = d.id_reserva
                INNER JOIN tbVehiculos v ON v.id_vehiculo = r.id_vehiculo
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                WHERE d.id_devolucion = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id_devolucion]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function buscarPorContrato($noContrato)
    {
        $db = Database::connect();
        $sql = "SELECT r.id_reserva, r.id_cliente, r.id_vehiculo, r.fecha_recogida, r.fecha_entrega,
                       c.nombre, c.apellido,
                       v.marca, v.modelo, v.numero_placa, v.precio_dia,
                       ct.numero_contrato
                FROM tbReservas r
                INNER JOIN tbClientes c ON r.id_cliente = c.id_cliente
                INNER JOIN tbVehiculos v ON r.id_vehiculo = v.id_vehiculo
                INNER JOIN tbContratos ct ON r.id_reserva = ct.id_reserva
                WHERE ct.numero_contrato = ? AND r.estado IN ('confirmada', 'en_curso')";
        $stmt = $db->prepare($sql);
        $stmt->execute([$noContrato]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function registrarDevolucion($id_reserva, $data)
    {
        $db = Database::connect();
        try {
            $db->beginTransaction();

            // Insertar en tbDevoluciones
            $sql1 = "INSERT INTO tbDevoluciones (id_reserva, fecha_devolucion_real, km_final, combustible, observaciones, estado)
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute([
                $id_reserva,
                $data['fecha_real'],
                $data['km_final'],
                $data['combustible'],
                $data['observaciones'],
                $data['estado']
            ]);
            $id_devolucion = $db->lastInsertId();

            // Actualizar estado de la reserva a 'completada'
            $db->prepare("UPDATE tbReservas SET estado = 'completada' WHERE id_reserva = ?")->execute([$id_reserva]);

            // Actualizar estado del vehículo a 'disponible'
            $db->prepare("UPDATE tbVehiculos SET estado = 'disponible' WHERE id_vehiculo = (SELECT id_vehiculo FROM tbReservas WHERE id_reserva = ?)")->execute([$id_reserva]);

            // Registrar multas
            if (!empty($data['multas'])) {
                foreach ($data['multas'] as $multa) {
                    $sqlM = "INSERT INTO tbMultas (id_reserva, id_devolucion, tipo, monto, motivo, pagada)
                             VALUES (?, ?, ?, ?, ?, 0)";
                    $stmtM = $db->prepare($sqlM);
                    $stmtM->execute([$id_reserva, $id_devolucion, $multa['tipo'], $multa['monto'], $multa['motivo']]);
                }
            }

            $db->commit();
            return $id_devolucion;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}