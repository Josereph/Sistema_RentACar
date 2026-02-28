// assets/js/asistencia.js
const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

function pad(n) {
    return String(n).padStart(2, '0');
}

function actualizarReloj() {
    const now = new Date();
    document.getElementById('reloj').textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    document.getElementById('fecha').textContent = `${dias[now.getDay()]} ${now.getDate()} de ${meses[now.getMonth()]} de ${now.getFullYear()}`;
}

actualizarReloj();
setInterval(actualizarReloj, 1000);

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d", { willReadFrequently: true });
const placeholder = document.getElementById("camera-placeholder");

let scanning = false;

function encenderCamara() {
    navigator.mediaDevices
        .getUserMedia({ video: true }) // ← cambia esto
        .then(function (stream) {
            scanning = true;
            placeholder.style.display = "none";
            canvasElement.hidden = false;
            video.setAttribute("playsinline", true);
            video.srcObject = stream;
            video.play();
            tick();
            scan();
        })
        .catch(err => {
            placeholder.textContent = "Error: Acceso denegado a la cámara";
            console.error(err);
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
    if (canvasElement.hidden) return;
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        scan();
    }
    if (scanning) requestAnimationFrame(tick);
}

function scan() {
    if (!scanning) return;
    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
    const code = jsQR(imageData.data, canvasElement.width, canvasElement.height);
    if (code) {
        console.log("QR detectado:", code.data);
        buscarCodigo(code.data);
        scanning = false;
        setTimeout(() => { scanning = true; tick(); }, 3000);
    } else {
        if (scanning) setTimeout(scan, 150);
    }
}

let closeTimer = null;

function buscarCodigo(respuesta) {
    let idParaBuscar;
    try {
        const objetoQR = JSON.parse(respuesta);
        if (!objetoQR.u) throw new Error("QR sin ID");
        idParaBuscar = objetoQR.u;
    } catch (e) {
        console.error("QR inválido:", e);
        mostrarModal('desconocido', '!', 'QR INVÁLIDO', 'Formato incorrecto', '');
        return;
    }
    console.log("Buscando Usuario con ID:", idParaBuscar);
    fetch('/Sistema_RentACar/index.php?controller=Asistencia&action=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_usuario=' + encodeURIComponent(idParaBuscar)
    })
    .then(r => r.json())
    .then(res => {
        console.log("Respuesta servidor:", res);
        if (res.success) {
            mostrarModal('bienvenido', '✓', res.estado === 'entrada' ? 'ENTRADA REGISTRADA' : 'SALIDA REGISTRADA', res.nombre || 'Usuario', idParaBuscar);
        } else {
            mostrarModal('desconocido', '!', res.mensaje || 'Error', '', idParaBuscar);
        }
    })
    .catch(err => {
        console.error("Error fetch:", err);
        mostrarModal('desconocido', '!', 'Error de conexión', '', idParaBuscar);
    });
}

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

window.addEventListener('load', encenderCamara);