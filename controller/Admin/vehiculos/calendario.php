<?php
$titulo = 'Calendario de Disponibilidad';
$seccion = 'vehiculos';
include __DIR__ . '/../layouts/admin_header.php';
include __DIR__ . '/../layouts/admin_navbar.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-calendar-alt me-2" style="color: #137fec;"></i>Calendario de Disponibilidad</h2>
            <p class="text-muted">Visualiza las reservas activas y la disponibilidad de vehículos.</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '<?= url('index.php?controller=Vehiculos&action=getEventos') ?>',
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                return false;
            }
        }
    });
    calendar.render();
});
</script>

<?php include __DIR__ . '/../layouts/admin_footer.php'; ?>