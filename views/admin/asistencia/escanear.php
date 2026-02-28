<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escáner QR - Asistencia</title>
    <!-- Librería jsQR desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <!-- Estilos -->
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/asistencia.css">
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
        <a href="/Sistema_RentACar/index.php?controller=AsistenciaAdmin&action=index" class="btn-camera">Volver</a>
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

    <script>
        // Verificar que jsQR esté cargada
        if (typeof jsQR === 'undefined') {
            console.error('ERROR: jsQR no se cargó. Verifica el enlace.');
            document.getElementById('camera-placeholder').innerText = 'Error: Librería QR no cargada';
        } else {
            console.log('jsQR cargada correctamente');
        }

        // Reloj
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        function pad(n) { return String(n).padStart(2, '0'); }

        function actualizarReloj() {
            const now = new Date();
            document.getElementById('reloj').textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            document.getElementById('fecha').textContent = `${dias[now.getDay()]} ${now.getDate()} de ${meses[now.getMonth()]} de ${now.getFullYear()}`;
        }
        actualizarReloj();
        setInterval(actualizarReloj, 1000);

        // Cámara
        const video = document.createElement("video");
        const canvasElement = document.getElementById("qr-canvas");
        const canvas = canvasElement.getContext("2d", { willReadFrequently: true });
        const placeholder = document.getElementById("camera-placeholder");

        let scanning = false;

        function encenderCamara() {
            console.log('Intentando encender cámara...');
            navigator.mediaDevices
                .getUserMedia({ video: true })
                .then(function (stream) {
                    console.log('Cámara encendida');
                    scanning = true;
                    placeholder.style.display = "none";
                    canvasElement.hidden = false;
                    video.setAttribute("playsinline", true);
                    video.srcObject = stream;
                    video.play();
                    tick();
                })
                .catch(err => {
                    console.error('Error al acceder a la cámara:', err);
                    placeholder.textContent = "Error: Acceso denegado a la cámara";
                });
        }

        function cerrarCamara() {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach((track) => track.stop());
            }
            scanning = false;
            canvasElement.hidden = true;
            placeholder.style.display = "flex";
            placeholder.textContent = "Cámara desactivada";
        }

        function tick() {
            if (!scanning || canvasElement.hidden) return;
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                scan();
            }
            requestAnimationFrame(tick);
        }

        function scan() {
            if (!scanning) return;
            const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            const code = jsQR(imageData.data, canvasElement.width, canvasElement.height);
            if (code) {
                console.log('QR detectado:', code.data);
                scanning = false; // pausar escaneo
                buscarCodigo(code.data);
                setTimeout(() => { scanning = true; }, 3000); // reactivar después de 3 seg
            } else {
                setTimeout(scan, 150);
            }
        }

        function buscarCodigo(respuesta) {
    console.log('Procesando QR:', respuesta);
    let idParaBuscar;
    try {
        const objetoQR = JSON.parse(respuesta);
        if (!objetoQR.u) throw new Error("QR sin ID");
        idParaBuscar = objetoQR.u;
    } catch (e) {
        // Si no es JSON, asumimos que es ID directo (para compatibilidad)
        idParaBuscar = respuesta;
    }
    console.log('ID a enviar:', idParaBuscar);

    fetch('/Sistema_RentACar/index.php?controller=AsistenciaAdmin&action=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'qrData=' + encodeURIComponent(respuesta) // Enviamos el texto completo
    })
    .then(r => r.json())
    .then(res => {
        console.log('Respuesta del servidor:', res);
        if (res.success) {
            mostrarModal('bienvenido', '✓', res.estado === 'entrada' ? 'ENTRADA REGISTRADA' : 'SALIDA REGISTRADA', res.nombre || 'Usuario', idParaBuscar);
        } else {
            mostrarModal('desconocido', '!', res.mensaje || 'Error', '', idParaBuscar);
        }
    })
    .catch(err => {
        console.error('Error en fetch:', err);
        mostrarModal('desconocido', '!', 'Error de conexión', '', idParaBuscar);
    });
}

        let closeTimer = null;
        function mostrarModal(tipo, icono, estado, nombreTexto, codigo) {
            clearTimeout(closeTimer);
            const modal = document.getElementById('modal');
            const overlay = document.getElementById('overlay');
            modal.className = 'modal ' + tipo;
            document.getElementById('icono').textContent = icono;
            document.getElementById('estado').textContent = estado;
            document.getElementById('nombre').textContent = nombreTexto;
            document.getElementById('codigoTexto').textContent = "ID: " + codigo;
            overlay.classList.add('show');
            closeTimer = setTimeout(() => {
                overlay.classList.remove('show');
            }, 3000);
        }

        document.getElementById('overlay').addEventListener('click', function() {
            this.classList.remove('show');
        });

        // Opcional: encender cámara automáticamente al cargar
        window.addEventListener('load', encenderCamara);
    </script>
</body>
</html>