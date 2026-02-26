<?php include "../view/layout/header.php" ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-car"></i> Gestión de Vehículos</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVehiculo">
                <i class="fas fa-plus"></i> Nuevo Vehículo
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaVehiculos" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Marca</th>
                            <th>Color</th>
                            <th>Tipo</th>
                            <th>Capacidad</th>
                            <th>Placa</th>
                            <th>Precio/Día</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL AGREGAR / EDITAR ================= -->
<div class="modal fade" id="modalVehiculo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Agregar Vehículo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formVehiculo">
                <div class="modal-body row g-3">

                    <input type="hidden" name="id_vehiculo" id="vehiculo_id">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="car_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Año</label>
                        <input type="number" name="year" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Color</label>
                        <input type="text" name="color" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tipo</label>
                        <select name="tipo_vehiculo" class="form-control" required>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Pick-up">Pick-up</option>
                            <option value="Convertibles">Convertibles</option>
                            <option value="Premium">Premium</option>
                            <option value="Minivan">Minivan</option>
                            <option value="Compacto">Compacto</option>
                            <option value="Mini">Mini</option>
                            <option value="Crossover">Crossover</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Capacidad</label>
                        <input type="number" name="capacidad" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Placa</label>
                        <input type="text" name="numero_placa" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Precio por Día</label>
                        <input type="number" step="0.01" name="precio_dia" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="disponible">Disponible</option>
                            <option value="rentado">Rentado</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL ELIMINAR ================= -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash"></i> Eliminar Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>¿Está seguro que desea eliminar este vehículo?</p>
                <input type="hidden" id="eliminar_id">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>

        </div>
    </div>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let tabla;

$(document).ready(function() {

    tabla = $('#tablaVehiculos').DataTable({
        ajax: {
            url: '../controllers/VehiculoController.php?action=listar',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id_vehiculo' },
            { data: 'car_name' },
            { data: 'modelo' },
            { data: 'year' },
            { data: 'marca' },
            { data: 'color' },
            { data: 'tipo_vehiculo' },
            { data: 'capacidad' },
            { data: 'numero_placa' },
            {
                data: 'precio_dia',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { data: 'descripcion' },
            { data: 'estado' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm btn-editar" data-id="${row.id_vehiculo}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-eliminar" data-id="${row.id_vehiculo}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Guardar
    $('#formVehiculo').submit(function(e){
        e.preventDefault();

        $.post('../controllers/VehiculoController.php?action=guardar',
            $(this).serialize(),
            function(res){
                if(res.success){
                    Swal.fire('Éxito', res.message, 'success');
                    $('#modalVehiculo').modal('hide');
                    tabla.ajax.reload();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }, 'json');
    });

    // Editar
    $(document).on('click','.btn-editar',function(){
        let id = $(this).data('id');

        $.get('../controllers/VehiculoController.php?action=obtener&id='+id,
        function(data){
            $('#vehiculo_id').val(data.id_vehiculo);
            $('input[name="car_name"]').val(data.car_name);
            $('input[name="modelo"]').val(data.modelo);
            $('input[name="year"]').val(data.year);
            $('input[name="marca"]').val(data.marca);
            $('input[name="color"]').val(data.color);
            $('select[name="tipo_vehiculo"]').val(data.tipo_vehiculo);
            $('input[name="capacidad"]').val(data.capacidad);
            $('input[name="numero_placa"]').val(data.numero_placa);
            $('input[name="precio_dia"]').val(data.precio_dia);
            $('textarea[name="descripcion"]').val(data.descripcion);
            $('select[name="estado"]').val(data.estado);

            $('#modalVehiculo').modal('show');
        },'json');
    });

    // Eliminar
    $(document).on('click','.btn-eliminar',function(){
        $('#eliminar_id').val($(this).data('id'));
        $('#modalEliminar').modal('show');
    });

    $('#btnConfirmarEliminar').click(function(){
        let id = $('#eliminar_id').val();

        $.post('../controllers/VehiculoController.php?action=eliminar',
        {id:id},
        function(res){
            if(res.success){
                Swal.fire('Eliminado', res.message, 'success');
                $('#modalEliminar').modal('hide');
                tabla.ajax.reload();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },'json');
    });

});
</script>

</body>
</html>