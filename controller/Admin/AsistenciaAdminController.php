<?php
// controller/Admin/AsistenciaAdminController.php
require_once __DIR__ . '/BaseAdminController.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/Admin/Usuario.php';
require_once PROJECT_ROOT_FS . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class AsistenciaAdminController extends BaseAdminController
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
    }

    public function index()
    {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $sql = "SELECT a.*, u.nombre 
                FROM tbAsistencia a
                INNER JOIN tbUsuarios u ON a.id_usuario = u.id_usuario
                WHERE DATE(a.fecha_hora) = ?
                ORDER BY a.fecha_hora DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha]);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $titulo = 'Registro de Asistencia';
        $seccion = 'asistencia';
        require PROJECT_ROOT_FS . '/views/admin/asistencia/index.php';
    }

    public function qr()
{
    $id = $_GET['id'] ?? 0;
    if (!$id) {
        die('ID no proporcionado');
    }

    $usuario = Usuario::findById($id);
    if (!is_array($usuario) || empty($usuario)) {
        die('Usuario no encontrado');
    }

    $clave_secreta = 'mi_clave_secreta_2026';
    $payload = json_encode([
        "u" => $id,
        "t" => md5($id . $clave_secreta)
    ]);

    $qrCode = new QrCode(
        data: $payload,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::Low,
        size: 300,
        margin: 10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin
    );

    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    $dataUri = $result->getDataUri();

    $titulo = 'QR de ' . $usuario['nombre'];
    $seccion = 'asistencia';
    require PROJECT_ROOT_FS . '/views/admin/asistencia/qr.php';
}

    public function escanear()
    {
        // Vista independiente, sin layouts de admin
        require PROJECT_ROOT_FS . '/views/admin/asistencia/escanear.php';
        exit;
    }

    public function registrar()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
        exit;
    }

    $qrData = $_POST['qrData'] ?? null;
    if (!$qrData) {
        echo json_encode(['success' => false, 'mensaje' => 'QR no proporcionado']);
        exit;
    }

    $data = json_decode($qrData, true);
    if (!$data || !isset($data['u'])) {
        echo json_encode(['success' => false, 'mensaje' => 'QR inválido']);
        exit;
    }

    $id_usuario = $data['u'];
    $hash_recibido = $data['t'];
    $clave_secreta = 'mi_clave_secreta_2026';
    $hash_esperado = md5($id_usuario . $clave_secreta);

    if ($hash_recibido !== $hash_esperado) {
        echo json_encode(['success' => false, 'mensaje' => 'QR falsificado']);
        exit;
    }

    // Verificar que el usuario exista
    $usuario = Usuario::findById($id_usuario);
    if (!$usuario) {
        echo json_encode(['success' => false, 'mensaje' => 'Usuario no encontrado']);
        exit;
    }

    $fecha_hora = date('Y-m-d H:i:s');
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
    exit;
}
}