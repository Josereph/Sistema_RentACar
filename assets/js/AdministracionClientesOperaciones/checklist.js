// assets/js/AdministracionClientesOperaciones/checklist.js

let isDrawing = false;
let lastX = 0;
let lastY = 0;

function setEstado(idItem, estado){
  const card = document.querySelector(`.check-item[data-id-item="${idItem}"]`);
  const inp  = document.getElementById(`estado_${idItem}`);
  const txt  = document.getElementById(`estado_txt_${idItem}`);

  if (!card || !inp || !txt) return;

  card.classList.remove('is-ok','is-falla');

  if (estado === 'ok'){
    card.classList.add('is-ok');
    inp.value = 'ok';
    txt.textContent = 'OK';
  } else if (estado === 'falla'){
    card.classList.add('is-falla');
    inp.value = 'falla';
    txt.textContent = 'FALLA';
  } else {
    inp.value = '';
    txt.textContent = 'PENDIENTE';
  }

  updateChecklistProgress();
}

function marcarTodosOk(){
  document.querySelectorAll('.check-item').forEach(card => {
    const idItem = card.getAttribute('data-id-item');
    if (idItem) setEstado(idItem, 'ok');
  });
}

function updateChecklistProgress(){
  const total = document.querySelectorAll('.check-item').length;
  let done = 0;

  document.querySelectorAll('.check-item input[type="hidden"][id^="estado_"]').forEach(inp => {
    if (inp.value === 'ok' || inp.value === 'falla') done++;
  });

  const pct = total === 0 ? 0 : Math.round((done/total) * 100);

  const bar = document.getElementById('checklistProgress');
  const txt = document.getElementById('checklistPct');

  if (bar) bar.style.width = `${pct}%`;
  if (txt) txt.textContent = `${done} / ${total} completados (${pct}%)`;
}

function beforeSubmitChecklist(ev){
  // Antes de enviar, guardamos la firma en base64
  const data = getSignatureData();
  if (data) {
    const hidden = document.getElementById('firma_inspector_data');
    if (hidden) hidden.value = data;
  }

  // Validación: si hay pendientes, confirmar
  const total = document.querySelectorAll('.check-item').length;
  let done = 0;
  document.querySelectorAll('.check-item input[type="hidden"][id^="estado_"]').forEach(inp => {
    if (inp.value === 'ok' || inp.value === 'falla') done++;
  });

  if (done < total){
    const faltan = total - done;
    const ok = confirm(`Hay ${faltan} ítems sin revisar. ¿Desea guardar de todas formas?`);
    if (!ok){
      ev.preventDefault();
      return false;
    }
  }
  return true;
}

/* ---------------- FIRMA ---------------- */

function setupSignature(){
  const canvas = document.getElementById('signatureCanvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');

  // Ajustar canvas a tamaño real (evita blur)
  const resize = () => {
    const rect = canvas.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    canvas.width  = Math.floor(rect.width * dpr);
    canvas.height = Math.floor(rect.height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
  };

  resize();
  window.addEventListener('resize', resize);

  const getPos = (e) => {
    const rect = canvas.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return {
      x: clientX - rect.left,
      y: clientY - rect.top
    };
  };

  const start = (e) => {
    isDrawing = true;
    const p = getPos(e);
    lastX = p.x;
    lastY = p.y;
  };

  const move = (e) => {
    if (!isDrawing) return;
    e.preventDefault();
    const p = getPos(e);
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
    lastX = p.x;
    lastY = p.y;
  };

  const end = () => { isDrawing = false; };

  canvas.addEventListener('mousedown', start);
  canvas.addEventListener('mousemove', move);
  window.addEventListener('mouseup', end);

  canvas.addEventListener('touchstart', start, {passive:false});
  canvas.addEventListener('touchmove', move, {passive:false});
  window.addEventListener('touchend', end);
}

function clearSignature(){
  const canvas = document.getElementById('signatureCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  ctx.clearRect(0,0,canvas.width,canvas.height);
}

function getSignatureData(){
  const canvas = document.getElementById('signatureCanvas');
  if (!canvas) return '';
  // Si está vacío, no enviar basura
  const blank = document.createElement('canvas');
  blank.width = canvas.width;
  blank.height = canvas.height;
  if (canvas.toDataURL() === blank.toDataURL()) return '';
  return canvas.toDataURL('image/png');
}

/* Init */
document.addEventListener('DOMContentLoaded', () => {
  setupSignature();
  updateChecklistProgress();
});