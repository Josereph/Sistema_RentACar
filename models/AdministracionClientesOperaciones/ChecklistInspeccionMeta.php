<?php
require_once __DIR__ . '/../../config/db.php';

class ChecklistInspeccionMeta
{
    public static function getByDevolucion(int $id_devolucion): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbChecklistInspeccionMeta WHERE id_devolucion = ?");
        $stmt->execute([$id_devolucion]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function upsert(
        int $id_devolucion,
        string $inspector,
        string $fecha_inspeccion,
        int $km_inspeccion,
        string $nivel_combustible,
        ?string $observaciones_generales,
        ?string $firma_inspector
    ): void
    {
        $db = Database::connect();

        $sql = "INSERT INTO tbChecklistInspeccionMeta
                (id_devolucion, inspector, fecha_inspeccion, km_inspeccion, nivel_combustible, observaciones_generales, firma_inspector)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                  inspector = VALUES(inspector),
                  fecha_inspeccion = VALUES(fecha_inspeccion),
                  km_inspeccion = VALUES(km_inspeccion),
                  nivel_combustible = VALUES(nivel_combustible),
                  observaciones_generales = VALUES(observaciones_generales),
                  firma_inspector = VALUES(firma_inspector)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            $id_devolucion,
            $inspector,
            $fecha_inspeccion,
            $km_inspeccion,
            $nivel_combustible,
            $observaciones_generales,
            $firma_inspector
        ]);
    }
}