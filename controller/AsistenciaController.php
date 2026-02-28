<?php
// controller/AsistenciaController.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Admin/Usuario.php';

class AsistenciaController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        require __DIR__ . '/../views/asistencia.php';
    }

    public function registrar()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
            return;
        }

        $id_usuario = $_POST['id_usuario'] ?? null;
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'mensaje' => 'ID de usuario no proporcionado']);
            return;
        }

        $usuario = Usuario::findById($id_usuario);
        if (!$usuario) {
            echo json_encode(['success' => false, 'mensaje' => 'Usuario no encontrado']);
            return;
        }

        $fecha_hora = date('Y-m-d H:i:s');

        // Determinar si es entrada o salida (último registro del día)
        $sql = "SELECT * FROM tbAsistencia WHERE id_usuario = ? AND DATE(fecha_hora) = CURDATE() ORDER BY fecha_hora DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

        $tipo = 'entrada';
        if ($ultimo && $ultimo['tipo'] == 'entrada') {
            $tipo = 'salida';
        }

        $insert = "INSERT INTO tbAsistencia (id_usuario, fecha_hora, tipo) VALUES (?, ?, ?)";
        $stmtInsert = $this->db->prepare($insert);
        $success = $stmtInsert->execute([$id_usuario, $fecha_hora, $tipo]);

        if ($success) {
            echo json_encode([
                'success' => true,
                'estado' => $tipo,
                'mensaje' => $tipo == 'entrada' ? 'Entrada registrada' : 'Salida registrada',
                'nombre' => $usuario['nombre']
            ]);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al registrar']);
        }
    }
}