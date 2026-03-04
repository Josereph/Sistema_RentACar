<?php
$titulo = 'Checklist de Inspección';
$seccion = 'checklist';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_header.php';
include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_navbar.php';

$devolucion = $devolucion ?? null;
$items      = $items ?? [];
$checks     = $checks ?? [];
$id_devolucion = $devolucion['id_devolucion'] ?? ($_GET['id_devolucion'] ?? null);
?>
<div class="container-fluid mt-4">
    <!-- ... mismo contenido que admin ... -->
    <form action="<?= url('index.php?area=empleado&controller=ChecklistEmpleado&action=guardar') ?>" method="POST" id="formChecklist">
        <!-- ... -->
    </form>
</div>
<?php include PROJECT_ROOT_FS . '/views/empleado/layouts/empleado_footer.php'; ?>