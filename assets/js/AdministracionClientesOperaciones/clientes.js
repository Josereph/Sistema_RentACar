/* ===== JS - AdministracionClientesOperaciones ===== */

// ── TOAST NOTIFICATIONS ──────────────────────────────────────────────────────
function showToast(message, type = 'info', duration = 3500) {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const icons = { success: 'fa-check-circle', danger: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i>${message}`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(30px)';
    toast.style.transition = 'all .3s ease';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// ── MODAL HELPERS ─────────────────────────────────────────────────────────────
function openModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
}

function closeModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
}

// Cerrar modal al hacer click fuera
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
    document.body.style.overflow = '';
  }
});

// ── SIDEBAR MOBILE ────────────────────────────────────────────────────────────
function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('open');
}

// ── CONFIRM DELETE ────────────────────────────────────────────────────────────
function confirmDelete(msg = '¿Está seguro de eliminar este registro?') {
  return confirm(msg);
}

// ── CRUD CLIENTES ─────────────────────────────────────────────────────────────
function editCliente(id) {
  // Aquí harías fetch al servidor para obtener datos del cliente
  fetch(`../../controller/AdministracionClientesOperaciones/ClienteController.php?action=get&id=${id}`)
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const d = data.cliente;
        document.getElementById('edit_id').value        = d.id || '';
        document.getElementById('edit_nombre').value    = d.nombre || '';
        document.getElementById('edit_apellido').value  = d.apellido || '';
        document.getElementById('edit_email').value     = d.email || '';
        document.getElementById('edit_telefono').value  = d.telefono || '';
        document.getElementById('edit_dui').value       = d.dui || '';
        document.getElementById('edit_nit').value       = d.nit || '';
        document.getElementById('edit_direccion').value = d.direccion || '';
        document.getElementById('edit_estado').value    = d.estado || 'activo';
        openModal('modalEditCliente');
      }
    })
    .catch(() => {
      // Demo: llenar con datos ficticios para visualización
      document.getElementById('edit_id').value        = id;
      document.getElementById('edit_nombre').value    = 'Juan Carlos';
      document.getElementById('edit_apellido').value  = 'Martínez';
      document.getElementById('edit_email').value     = 'jmartinez@email.com';
      document.getElementById('edit_telefono').value  = '7654-3210';
      document.getElementById('edit_dui').value       = '03456789-0';
      document.getElementById('edit_estado').value    = 'activo';
      openModal('modalEditCliente');
    });
}

function deleteCliente(id) {
  if (!confirmDelete('¿Eliminar este cliente? Esta acción no se puede deshacer.')) return;
  fetch(`../../controller/AdministracionClientesOperaciones/ClienteController.php?action=delete&id=${id}`, { method: 'POST' })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const row = document.getElementById(`row-${id}`);
        if (row) { row.style.opacity = '0'; setTimeout(() => row.remove(), 300); }
        showToast('Cliente eliminado correctamente', 'success');
      } else {
        showToast('Error al eliminar el cliente', 'danger');
      }
    })
    .catch(() => {
      // Demo
      const row = document.getElementById(`row-${id}`);
      if (row) { row.style.transition = 'opacity .3s'; row.style.opacity = '0'; setTimeout(() => row.remove(), 300); }
      showToast('Cliente eliminado (demo)', 'success');
    });
}

// ── BÚSQUEDA EN TABLA ─────────────────────────────────────────────────────────
function initTableSearch(inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  if (!input || !table) return;

  input.addEventListener('input', () => {
    const term = input.value.toLowerCase();
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
  });
}

// ── CHECKLIST ─────────────────────────────────────────────────────────────────
function initChecklist() {
  document.querySelectorAll('.checklist-item').forEach(item => {
    item.addEventListener('click', () => {
      item.classList.toggle('checked');
      const cb   = item.querySelector('.custom-checkbox');
      const icon = cb ? cb.querySelector('i') : null;
      if (cb) cb.innerHTML = item.classList.contains('checked') ? '<i class="fas fa-check"></i>' : '';
      updateChecklistProgress();
    });
  });
}

function updateChecklistProgress() {
  const all     = document.querySelectorAll('.checklist-item').length;
  const checked = document.querySelectorAll('.checklist-item.checked').length;
  const pct = all ? Math.round((checked / all) * 100) : 0;
  const bar   = document.getElementById('checklistProgress');
  const label = document.getElementById('checklistPct');
  if (bar)   bar.style.width   = pct + '%';
  if (label) label.textContent = `${checked} / ${all} ítems completados (${pct}%)`;
}

// ── STAR RATING ───────────────────────────────────────────────────────────────
function initStarRating() {
  document.querySelectorAll('.star-rating').forEach(wrapper => {
    const stars = wrapper.querySelectorAll('span');
    stars.forEach((star, i) => {
      star.addEventListener('mouseover', () => {
        stars.forEach((s, j) => s.classList.toggle('active', j <= i));
      });
      star.addEventListener('click', () => {
        const val = i + 1;
        wrapper.dataset.value = val;
        const hidden = document.getElementById(wrapper.dataset.target);
        if (hidden) hidden.value = val;
        stars.forEach((s, j) => s.classList.toggle('active', j <= i));
      });
    });
    wrapper.addEventListener('mouseleave', () => {
      const val = parseInt(wrapper.dataset.value || 0);
      stars.forEach((s, j) => s.classList.toggle('active', j < val));
    });
  });
}

// ── SIGNATURE PAD ─────────────────────────────────────────────────────────────
function initSignaturePad(canvasId) {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  const ctx   = canvas.getContext('2d');
  let drawing = false;

  canvas.width  = canvas.offsetWidth;
  canvas.height = 140;

  ctx.strokeStyle = '#00B4D8';
  ctx.lineWidth   = 2;
  ctx.lineCap     = 'round';

  const getPos = (e) => {
    const rect = canvas.getBoundingClientRect();
    const src  = e.touches ? e.touches[0] : e;
    return { x: src.clientX - rect.left, y: src.clientY - rect.top };
  };

  canvas.addEventListener('mousedown',  e => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
  canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
  canvas.addEventListener('mouseup',    () => drawing = false);
  canvas.addEventListener('mouseleave', () => drawing = false);

  canvas.addEventListener('touchstart',  e => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, { passive: false });
  canvas.addEventListener('touchmove',   e => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }, { passive: false });
  canvas.addEventListener('touchend',    () => drawing = false);
}

function clearSignature(canvasId) {
  const canvas = document.getElementById(canvasId);
  if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
}

function getSignatureData(canvasId) {
  const canvas = document.getElementById(canvasId);
  return canvas ? canvas.toDataURL('image/png') : null;
}

// ── PHOTO UPLOAD PREVIEW ──────────────────────────────────────────────────────
function initPhotoSlots() {
  document.querySelectorAll('.photo-slot[data-input]').forEach(slot => {
    const inputId = slot.dataset.input;
    let input = document.getElementById(inputId);
    if (!input) {
      input = document.createElement('input');
      input.type = 'file'; input.id = inputId; input.accept = 'image/*';
      input.style.display = 'none';
      document.body.appendChild(input);
    }
    slot.addEventListener('click', () => input.click());
    input.addEventListener('change', () => {
      if (input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          slot.innerHTML = `<img src="${e.target.result}" alt="foto">`;
        };
        reader.readAsDataURL(input.files[0]);
      }
    });
  });
}

// ── CALENDAR ──────────────────────────────────────────────────────────────────
const CalendarApp = {
  currentDate: new Date(),

  events: {
    // Formato: 'YYYY-MM-DD': [{ title, type, vehiculo }]
  },

  // Inyecta eventos desde PHP (llamar desde la vista con JSON)
  loadEvents(jsonEvents) {
    this.events = jsonEvents || {};
  },

  getDaysInMonth(year, month) { return new Date(year, month + 1, 0).getDate(); },
  getFirstDayOfMonth(year, month) { return new Date(year, month, 1).getDay(); },

  pad(n) { return String(n).padStart(2, '0'); },

  dateKey(y, m, d) { return `${y}-${this.pad(m + 1)}-${this.pad(d)}`; },

  render() {
    const grid   = document.getElementById('calendarGrid');
    const title  = document.getElementById('calendarTitle');
    if (!grid || !title) return;

    const y = this.currentDate.getFullYear();
    const m = this.currentDate.getMonth();

    const months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    title.textContent = `${months[m]} ${y}`;

    const daysInMonth = this.getDaysInMonth(y, m);
    const firstDay    = this.getFirstDayOfMonth(y, m);
    const prevDays    = this.getDaysInMonth(y, m - 1);

    const today = new Date();
    const todayKey = this.dateKey(today.getFullYear(), today.getMonth(), today.getDate());

    grid.innerHTML = '';

    // Días del mes anterior
    for (let i = firstDay - 1; i >= 0; i--) {
      const day = prevDays - i;
      grid.appendChild(this.createDayEl(day, null, true));
    }

    // Días del mes actual
    for (let d = 1; d <= daysInMonth; d++) {
      const key    = this.dateKey(y, m, d);
      const isToday = key === todayKey;
      const evs    = this.events[key] || [];
      grid.appendChild(this.createDayEl(d, evs, false, isToday, key));
    }

    // Completar última fila
    const total = firstDay + daysInMonth;
    const rest  = total % 7 === 0 ? 0 : 7 - (total % 7);
    for (let d = 1; d <= rest; d++) {
      grid.appendChild(this.createDayEl(d, null, true));
    }
  },

  createDayEl(day, events, otherMonth, isToday, key) {
    const el = document.createElement('div');
    el.className = 'cal-day' + (otherMonth ? ' other-month' : '') + (isToday ? ' today' : '');

    let html = `<span class="cal-date">${day}</span>`;

    if (events && events.length) {
      events.forEach(ev => {
        html += `<span class="cal-event ${ev.type}" title="${ev.vehiculo}">${ev.vehiculo}</span>`;
      });
    }

    el.innerHTML = html;

    if (!otherMonth && key) {
      el.dataset.key = key;
      el.addEventListener('click', () => CalendarApp.onDayClick(key, day));
    }

    return el;
  },

  onDayClick(key, day) {
    document.getElementById('calDayLabel') && (document.getElementById('calDayLabel').textContent = `Fecha: ${key}`);
    openModal('modalDayDetail');
  },

  prevMonth() {
    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
    this.render();
  },

  nextMonth() {
    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
    this.render();
  }
};

// ── INIT ──────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initTableSearch('searchInput', 'clientesTable');
  initChecklist();
  initStarRating();
  initPhotoSlots();
  initSignaturePad('signatureCanvas');

  // Cargar eventos de ejemplo para el calendario
  const sampleEvents = {};
  const today = new Date();
  const y = today.getFullYear();
  const m = String(today.getMonth() + 1).padStart(2, '0');
  sampleEvents[`${y}-${m}-05`] = [{ title: 'Reserva', type: 'reservado',  vehiculo: 'P-1234' }];
  sampleEvents[`${y}-${m}-10`] = [{ title: 'Ocupado',  type: 'ocupado',   vehiculo: 'P-5678' }];
  sampleEvents[`${y}-${m}-15`] = [{ title: 'Mantenim', type: 'mantenimiento', vehiculo: 'P-9012' }];
  sampleEvents[`${y}-${m}-${String(today.getDate()).padStart(2,'0')}`] = [
    { title: 'Disponible', type: 'disponible', vehiculo: 'P-3456' }
  ];

  CalendarApp.loadEvents(sampleEvents);
  CalendarApp.render();
});
