// ============================================
// GO CAR - Script principal
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ========================================
    // MODAL DE PAGO
    // ========================================
    const overlay = document.getElementById('paymentOverlay');
    const btnOpen = document.getElementById('btnOpenPayment');
    const btnClose = document.getElementById('btnClosePayment');
    const btnConfirm = document.getElementById('btnConfirmPayment');
    const btnDone = document.getElementById('btnCloseSuccess');
    const step1 = document.getElementById('paymentStep1');
    const step2 = document.getElementById('paymentStep2');

    // pestañas de metodo de pago
    const tabs = document.querySelectorAll('.payment-tab');
    const forms = {
        card: document.getElementById('formCard'),
        paypal: document.getElementById('formPaypal'),
        cash: document.getElementById('formCash')
    };

    // abrir modal
    if (btnOpen) {
        btnOpen.addEventListener('click', function () {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // cerrar modal
    function closeModal() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        // Reset to step 1
        if (step1) step1.style.display = '';
        if (step2) step2.style.display = 'none';
    }

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnDone) btnDone.addEventListener('click', closeModal);

    // cerrar al hacer click en el overlay
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
    }

    // cerrar al presionar la tecla escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
            closeModal();
        }
    });

    // cambiar pestañas de metodo de pago
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            // quitar active de todas las pestañas
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');

            // Hide all forms, show selected
            var method = tab.getAttribute('data-method');
            Object.keys(forms).forEach(function (key) {
                if (forms[key]) {
                    forms[key].style.display = key === method ? '' : 'none';
                }
            });
        });
    });

    // confirmar pago
    if (btnConfirm) {
        btnConfirm.addEventListener('click', function () {
            // generar id de reserva
            var resId = 'RC-' + Math.floor(1000 + Math.random() * 9000);
            var resIdEl = document.getElementById('reservationId');
            if (resIdEl) resIdEl.textContent = '#' + resId;

            // obtener datos de la reserva
            var vehicleName = document.querySelector('.vehicle-name');
            var priceAmount = document.querySelector('.price-amount');
            var totalAmount = document.querySelector('.total-amount');
            var activeTab = document.querySelector('.payment-tab.active');

            // crear objeto de reserva
            var reservation = {
                id: resId,
                vehicle: vehicleName ? vehicleName.textContent : 'Desconocido',
                dailyRate: priceAmount ? priceAmount.textContent : '$0',
                total: totalAmount ? totalAmount.textContent : '$0',
                paymentMethod: activeTab ? activeTab.getAttribute('data-method') : 'card',
                date: new Date().toISOString(),
                status: 'Confirmada'
            };

            // guardar en localStorage
            var reservations = JSON.parse(localStorage.getItem('gocar_reservations') || '[]');
            reservations.push(reservation);
            localStorage.setItem('gocar_reservations', JSON.stringify(reservations));

            // mostrar paso de exito
            step1.style.display = 'none';
            step2.style.display = '';
        });
    }

    // ========================================
    // CALENDARIO DE DISPONIBILIDAD
    // ========================================
    var calDays = document.getElementById('calDays');
    var calLabel = document.getElementById('calMonthLabel');
    var calPrev = document.getElementById('calPrev');
    var calNext = document.getElementById('calNext');

    if (calDays) {
        var today = new Date();
        var calYear = today.getFullYear();
        var calMonth = today.getMonth();
        var selStart = null;
        var selEnd = null;

        // Generar dias no disponibles pseudo-aleatorios por mes (determinista desde el mes)
        function getUnavailableDays(year, month) {
            var seed = year * 100 + month;
            var days = [];
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            for (var d = 1; d <= daysInMonth; d++) {
                // hash simple para crear dias no disponibles "aleatorios" consistentes
                var hash = ((seed * 31 + d * 17) % 100);
                if (hash < 18) { // ~18% dias no disponibles
                    days.push(d);
                }
            }
            return days;
        }

        var monthNames = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        function renderCalendar() {
            var unavailable = getUnavailableDays(calYear, calMonth);
            var firstDay = new Date(calYear, calMonth, 1).getDay();
            // Convert Sunday=0 to Monday-first: Mon=0, Tue=1, ... Sun=6
            var startOffset = (firstDay + 6) % 7;
            var daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();

            calLabel.textContent = monthNames[calMonth] + ' ' + calYear;

            var html = '';

            // celdas vacias antes del dia 1
            for (var e = 0; e < startOffset; e++) {
                html += '<span class="cal-day cal-day-empty"></span>';
            }

            for (var d = 1; d <= daysInMonth; d++) {
                var classes = 'cal-day';
                var dateObj = new Date(calYear, calMonth, d);
                var isUnavailable = unavailable.indexOf(d) !== -1;
                var isPast = dateObj < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                var isToday = (d === today.getDate() && calMonth === today.getMonth() && calYear === today.getFullYear());

                if (isPast) {
                    classes += ' cal-day-past';
                } else if (isUnavailable) {
                    classes += ' cal-day-unavailable';
                }

                if (isToday) {
                    classes += ' cal-day-today';
                }

                // Selection
                var dateStr = calYear + '-' + String(calMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                if (selStart && dateStr === selStart) {
                    classes += ' cal-day-selected';
                } else if (selEnd && dateStr === selEnd) {
                    classes += ' cal-day-selected';
                } else if (selStart && selEnd) {
                    if (dateStr > selStart && dateStr < selEnd) {
                        classes += ' cal-day-range';
                    }
                }

                html += '<span class="' + classes + '" data-date="' + dateStr + '">' + d + '</span>';
            }

            calDays.innerHTML = html;

            // eventos de click
            var dayCells = calDays.querySelectorAll('.cal-day:not(.cal-day-empty):not(.cal-day-past):not(.cal-day-unavailable)');
            dayCells.forEach(function (cell) {
                cell.addEventListener('click', function () {
                    var clickedDate = cell.getAttribute('data-date');
                    if (!selStart || (selStart && selEnd)) {
                        // iniciar nueva seleccion
                        selStart = clickedDate;
                        selEnd = null;
                    } else {
                        // establecer fecha final
                        if (clickedDate < selStart) {
                            selEnd = selStart;
                            selStart = clickedDate;
                        } else if (clickedDate === selStart) {
                            return;
                        } else {
                            selEnd = clickedDate;
                        }
                        // actualizar las fechas del formulario de reserva
                        updateBookingDates();
                    }
                    renderCalendar();
                });
            });
        }

        function updateBookingDates() {
            if (!selStart || !selEnd) return;
            var startInput = document.querySelector('.date-row .form-group:first-child input[type="date"]');
            var endInput = document.querySelector('.date-row .form-group:last-child input[type="date"]');
            if (startInput) startInput.value = selStart;
            if (endInput) endInput.value = selEnd;

            // calcular diferencia de dias
            var d1 = new Date(selStart);
            var d2 = new Date(selEnd);
            var diffDays = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
            if (diffDays < 1) diffDays = 1;

            // actualizar desglose de precios
            var priceEl = document.querySelector('.price-amount');
            if (priceEl) {
                var dailyRate = parseFloat(priceEl.textContent.replace('$', '')) || 0;
                var subtotal = dailyRate * diffDays;
                var insurance = 45;
                var serviceFee = 12.50;
                var total = subtotal + insurance + serviceFee;

                var priceLines = document.querySelectorAll('.price-line');
                if (priceLines[0]) {
                    priceLines[0].querySelector('span:first-child').textContent = '$' + dailyRate + ' x ' + diffDays + ' días';
                    priceLines[0].querySelector('span:last-child').textContent = '$' + subtotal.toFixed(2);
                }

                var totalEl = document.querySelector('.total-amount');
                if (totalEl) totalEl.textContent = '$' + total.toFixed(2);

                // actualizar boton de confirmacion en el modal
                var confirmBtn = document.getElementById('btnConfirmPayment');
                if (confirmBtn) {
                    confirmBtn.innerHTML = '<i class="fas fa-lock"></i> Confirmar Pago — $' + total.toFixed(2);
                }

                // actualizar total del modal
                var modalTotalRows = document.querySelectorAll('.payment-summary-total span');
                if (modalTotalRows.length >= 2) {
                    modalTotalRows[1].textContent = '$' + total.toFixed(2);
                }
            }
        }

        calPrev.addEventListener('click', function () {
            calMonth--;
            if (calMonth < 0) { calMonth = 11; calYear--; }
            renderCalendar();
        });

        calNext.addEventListener('click', function () {
            calMonth++;
            if (calMonth > 11) { calMonth = 0; calYear++; }
            renderCalendar();
        });

        renderCalendar();
    }

    // ========================================
    // PANEL DE ADMINISTRACION - Cargar Reservas
    // ========================================
    var adminTableBody = document.getElementById('adminTableBody');
    var statTotal = document.getElementById('statTotalReservations');
    var statRevenue = document.getElementById('statTotalRevenue');
    var statActive = document.getElementById('statActiveReservations');

    if (adminTableBody) {
        // datos de ejemplo
        var sampleReservations = [
            { id: 'RC-4821', vehicle: 'Toyota Corolla', dailyRate: '$85', total: '$312.50', paymentMethod: 'card', date: '2026-02-10T14:30:00Z', status: 'Confirmada' },
            { id: 'RC-3917', vehicle: 'Toyota RAV4', dailyRate: '$110', total: '$387.50', paymentMethod: 'paypal', date: '2026-02-11T09:15:00Z', status: 'Confirmada' },
            { id: 'RC-2654', vehicle: 'Vehículo de Lujo', dailyRate: '$200', total: '$657.50', paymentMethod: 'card', date: '2026-02-09T16:45:00Z', status: 'Completada' },
            { id: 'RC-1538', vehicle: 'Kia Picanto 2006', dailyRate: '$50', total: '$207.50', paymentMethod: 'cash', date: '2026-02-08T11:00:00Z', status: 'Cancelada' },
        ];

        // fusionar con localStorage
        var savedReservations = JSON.parse(localStorage.getItem('gocar_reservations') || '[]');
        var allReservations = sampleReservations.concat(savedReservations);

        // calcular estadisticas
        var totalRevenue = 0;
        var activeCount = 0;
        allReservations.forEach(function (r) {
            var amount = parseFloat(r.total.replace('$', '').replace(',', '')) || 0;
            if (r.status !== 'Cancelada') totalRevenue += amount;
            if (r.status === 'Confirmada') activeCount++;
        });

        if (statTotal) statTotal.textContent = allReservations.length;
        if (statRevenue) statRevenue.textContent = '$' + totalRevenue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        if (statActive) statActive.textContent = activeCount;

        // renderizar filas de la tabla
        allReservations.forEach(function (r) {
            var row = document.createElement('tr');

            // clase de badge de estado
            var statusClass = 'badge-confirmed';
            if (r.status === 'Completada') statusClass = 'badge-completed';
            if (r.status === 'Cancelada') statusClass = 'badge-cancelled';

            // icono de metodo de pago
            var methodIcon = 'fa-credit-card';
            var methodLabel = 'Tarjeta';
            if (r.paymentMethod === 'paypal') { methodIcon = 'fab fa-paypal'; methodLabel = 'PayPal'; }
            else if (r.paymentMethod === 'cash') { methodIcon = 'fa-money-bill-wave'; methodLabel = 'Efectivo'; }
            else { methodIcon = 'fa-credit-card'; }

            // formato de fecha
            var d = new Date(r.date);
            var dateStr = d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });

            row.innerHTML =
                '<td><span class="res-id">#' + r.id + '</span></td>' +
                '<td>' + r.vehicle + '</td>' +
                '<td>' + dateStr + '</td>' +
                '<td><i class="fas ' + methodIcon + '"></i> ' + methodLabel + '</td>' +
                '<td><strong>' + r.total + '</strong></td>' +
                '<td><span class="status-badge ' + statusClass + '">' + r.status + '</span></td>';

            adminTableBody.appendChild(row);
        });
    }

});
