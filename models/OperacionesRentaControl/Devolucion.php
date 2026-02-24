<?php
// /models/OperacionesRentaControl/Devolucion.php

require_once __DIR__ . '/../../config/db.php';

class Devolucion
{
    public static function getById(int $id_devolucion): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbDevoluciones WHERE id_devolucion = ?");
        $stmt->execute([$id_devolucion]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}