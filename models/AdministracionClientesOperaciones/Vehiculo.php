<?php
require_once __DIR__ . '/../../config/db.php';

class Vehiculo
{
    public static function all()
    {
        $db = Database::connect();
        return $db->query("SELECT * FROM tbVehiculos ORDER BY id_vehiculo DESC")->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM tbVehiculos WHERE id_vehiculo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
{
    $db = Database::connect();
    $sql = "INSERT INTO tbVehiculos (car_name, modelo, year, marca, color, tipo_vehiculo, capacidad, numero_placa, precio_dia, descripcion, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt->execute([
        $data['car_name'],
        $data['modelo'],
        $data['year'],
        $data['marca'],
        $data['color'],
        $data['tipo_vehiculo'],
        $data['capacidad'],
        $data['numero_placa'],
        $data['precio_dia'],
        $data['descripcion'],
        $data['estado']
    ])) {
        return $db->lastInsertId();
    }
    return false;
}


public static function getImagenes($id)
{
    $carpeta = PROJECT_ROOT_FS . '/assets/img/vehiculos/';
    $patron = $carpeta . $id . '_*.{jpg,jpeg,png,gif}';
    $archivos = glob($patron, GLOB_BRACE);
    $imagenes = [];
    foreach ($archivos as $archivo) {
        $imagenes[] = '/Sistema_RentACar/assets/img/vehiculos/' . basename($archivo);
    }
    return $imagenes;
}


    public static function update($id, $data)
    {
        $db = Database::connect();
        $sql = "UPDATE tbVehiculos SET
                car_name = ?, modelo = ?, year = ?, marca = ?, color = ?, tipo_vehiculo = ?,
                capacidad = ?, numero_placa = ?, precio_dia = ?, descripcion = ?, estado = ?
                WHERE id_vehiculo = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $data['car_name'],
            $data['modelo'],
            $data['year'],
            $data['marca'],
            $data['color'],
            $data['tipo_vehiculo'],
            $data['capacidad'],
            $data['numero_placa'],
            $data['precio_dia'],
            $data['descripcion'],
            $data['estado'],
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tbVehiculos WHERE id_vehiculo = ?");
        return $stmt->execute([$id]);
    }

    // Métodos para estadísticas (ya existentes)
    public static function countDisponibles()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'disponible'")->fetchColumn();
    }

    public static function countEnRenta()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'rentado'")->fetchColumn();
    }

    public static function countMantenimiento()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbVehiculos WHERE estado = 'mantenimiento'")->fetchColumn();
    }

    public static function total()
    {
        $db = Database::connect();
        return $db->query("SELECT COUNT(*) FROM tbVehiculos")->fetchColumn();
    }

    

}