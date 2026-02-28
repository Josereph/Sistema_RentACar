<?php
$titulo = 'Reportes Avanzados';
$seccion = 'reportes';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_header.php';
include PROJECT_ROOT_FS . '/views/admin/layouts/admin_navbar.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-chart-pie me-2" style="color: #137fec;"></i>Reportes Avanzados</h2>
            <p class="text-muted">Seleccione el tipo de reporte y el rango de fechas para generar un PDF detallado.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="/Sistema_RentACar/index.php?controller=Reportes&action=generarPdfAvanzado" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de reporte *</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            <optgroup label="1. Dashboard ejecutivo (KPIs)">
                                <option value="kpi_reservas_estado">Reservas por estado</option>
                                <option value="kpi_ingresos_por_concepto">Ingresos por concepto</option>
                                <option value="kpi_flota_estado">Estado de la flota</option>
                                <option value="kpi_utilizacion_flota">Utilización de flota</option>
                                <option value="kpi_ingresos_por_dia">Ingresos por día</option>
                                <option value="kpi_ingresos_por_semana">Ingresos por semana</option>
                                <option value="kpi_ingresos_por_mes">Ingresos por mes</option>
                                <option value="kpi_ingresos_por_anio">Ingresos por año</option>
                                <option value="kpi_promedio_reservas_dia">Promedio reservas por día</option>
                                <option value="kpi_tasa_morosidad">Tasa de morosidad</option>
                                <option value="kpi_tasa_conversion">Tasa de conversión</option>
                                <option value="kpi_ingresos_netos_por_concepto">Ingresos netos por concepto</option>
                            </optgroup>
                            <optgroup label="2. Reservas">
                                <option value="reservas_por_dia">Reservas por día</option>
                                <option value="reservas_por_vehiculo">Reservas por vehículo</option>
                                <option value="duracion_promedio_renta">Duración promedio de renta</option>
                                <option value="reservas_por_hora">Reservas por hora</option>
                                <option value="reservas_por_dia_semana">Reservas por día de la semana</option>
                                <option value="reservas_por_tipo_vehiculo">Reservas por tipo de vehículo</option>
                                <option value="reservas_por_marca">Reservas por marca</option>
                                <option value="reservas_por_usuario">Reservas por usuario (empleado)</option>
                                <option value="reservas_por_rango_duracion">Reservas por rango de duración</option>
                                <option value="reservas_confirmadas_sin_pago">Reservas confirmadas sin pago</option>
                            </optgroup>
                            <optgroup label="3. Ingresos y Pagos">
                                <option value="pagos_por_metodo">Pagos por método</option>
                                <option value="pagos_por_proveedor">Pagos por proveedor</option>
                                <option value="pagos_fallidos">Pagos pendientes/fallidos</option>
                                <option value="pagos_reembolsados">Pagos reembolsados</option>
                                <option value="pagos_sin_referencia">Pagos sin referencia</option>
                                <option value="detalle_ingresos_por_cliente">Ingresos por cliente</option>
                                <option value="detalle_ingresos_por_reserva">Ingresos por reserva</option>
                            </optgroup>
                            <optgroup label="4. Contratos">
                                <option value="contratos_por_estado">Contratos por estado</option>
                                <option value="contratos_sin_pdf">Contratos sin PDF</option>
                                <option value="contratos_activos">Contratos activos</option>
                                <option value="contratos_finalizados">Contratos finalizados</option>
                                <option value="deposito_promedio">Depósito promedio</option>
                            </optgroup>
                            <optgroup label="5. Devoluciones">
                                <option value="devoluciones_por_estado">Devoluciones por estado</option>
                                <option value="devoluciones_tarde">Devoluciones tardías</option>
                                <option value="km_promedio_devolucion">Kilometraje promedio</option>
                                <option value="combustible_mas_comun">Combustible más común</option>
                                <option value="devoluciones_por_vehiculo">Devoluciones por vehículo</option>
                            </optgroup>
                            <optgroup label="6. Multas">
                                <option value="multas_por_tipo">Multas por tipo</option>
                                <option value="multas_pendientes">Multas pendientes</option>
                                <option value="multas_por_cliente">Multas por cliente</option>
                                <option value="multas_por_vehiculo">Multas por vehículo</option>
                                <option value="motivos_multa_mas_comunes">Motivos de multa más comunes</option>
                            </optgroup>
                            <optgroup label="7. Checklist">
                                <option value="items_mas_fallados">Items más fallados</option>
                                <option value="fallas_por_vehiculo">Fallas por vehículo</option>
                                <option value="inspecciones_por_inspector">Inspecciones por inspector</option>
                            </optgroup>
                            <optgroup label="8. Mantenimientos">
                                <option value="mantenimientos_por_tipo">Mantenimientos por tipo</option>
                                <option value="costo_mantenimiento_por_vehiculo">Costo mantenimiento por vehículo</option>
                            </optgroup>
                            <optgroup label="9. Vehículos">
                                <option value="vehiculos_por_estado">Vehículos por estado</option>
                                <option value="vehiculos_sin_reservas">Vehículos sin reservas</option>
                            </optgroup>
                            <optgroup label="10. Clientes">
                                <option value="clientes_nuevos">Clientes nuevos</option>
                                <option value="clientes_mas_reservas">Clientes con más reservas</option>
                            </optgroup>
                            <optgroup label="11. Usuarios">
                                <option value="usuarios_por_rol">Usuarios por rol</option>
                            </optgroup>
                            <optgroup label="13. Notificaciones">
                                <option value="notificaciones_por_estado">Notificaciones por estado</option>
                            </optgroup>
                            <optgroup label="14. Alertas / Integridad">
                                <option value="reservas_sin_pago">Reservas sin pago</option>
                                <option value="vehiculos_mantenimiento_sin_registro">Vehículos en mantenimiento sin registro</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control" value="<?= date('Y-m-t') ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-pdf me-2"></i>Generar PDF
                        </button>
                        <a href="/Sistema_RentACar/index.php?controller=Reportes&action=index" class="btn btn-outline-secondary ms-2">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include PROJECT_ROOT_FS . '/views/admin/layouts/admin_footer.php'; ?>