<?php
// /models/AdministracionClientesOperaciones/ChecklistItem.php

require_once __DIR__ . '/../../config/db.php';

class ChecklistItem
{
    public static function allActive(): array
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT id_item, nombre FROM tbChecklistItems WHERE activo = 1 ORDER BY id_item ASC");
        return $stmt->fetchAll();
    }

    public static function countActive(): int
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT COUNT(*) AS c FROM tbChecklistItems WHERE activo = 1");
        $row = $stmt->fetch();
        return (int)($row['c'] ?? 0);
    }
}