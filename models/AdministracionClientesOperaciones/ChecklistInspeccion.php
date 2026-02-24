<?php
// /models/AdministracionClientesOperaciones/ChecklistInspeccion.php

require_once __DIR__ . '/../../config/db.php';

class ChecklistInspeccion
{
    public static function getByDevolucion(int $id_devolucion): array
    {
        $db = Database::connect();
        $sql = "SELECT id_item, estado, nota
                FROM tbChecklistInspeccion
                WHERE id_devolucion = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_devolucion]);
        $rows = $stmt->fetchAll();

        // indexado por id_item
        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r['id_item']] = [
                'estado' => $r['estado'],
                'nota' => $r['nota']
            ];
        }
        return $map;
    }

    public static function upsert(int $id_devolucion, int $id_item, string $estado, ?string $nota = null): void
    {
        $db = Database::connect();

        // Tu tabla tiene UNIQUE(id_devolucion, id_item) => usamos ON DUPLICATE KEY UPDATE
        $sql = "INSERT INTO tbChecklistInspeccion (id_devolucion, id_item, estado, nota)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE estado = VALUES(estado), nota = VALUES(nota)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_devolucion, $id_item, $estado, $nota]);
    }
}