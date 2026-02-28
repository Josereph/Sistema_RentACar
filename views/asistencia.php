<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia - QR Scanner</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/asistencia.css">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
</head>
<body>
    <div class="titulo">Control de Asistencia</div>
    <div class="scanner-container">
        <div id="camera-placeholder" class="camera-placeholder">Esperando cámara...</div>
        <canvas hidden id="qr-canvas"></canvas>
    </div>
    <div class="controles">
        <button class="btn-camera" onclick="encenderCamara()">Activar Cámara</button>
        <button class="btn-camera" onclick="cerrarCamara()">Apagar</button>
    </div>
    <div class="reloj" id="reloj">00:00:00</div>
    <div class="fecha" id="fecha"></div>
    <div class="overlay" id="overlay">
        <div class="modal" id="modal">
            <div class="modal-icono" id="icono"></div>
            <div class="modal-estado" id="estado"></div>
            <div class="modal-nombre" id="nombre"></div>
            <div class="modal-codigo" id="codigoTexto"></div>
        </div>
    </div>
    <script src="/Sistema_RentACar/assets/js/asistencia.js"></script>
</body>
</html>