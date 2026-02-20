<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Vehículos Más Rentados</title>

    <style>
        table{
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }

        th, td{
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th{
            background-color: #f2f2f2;
        }

        h2{
            text-align:center;
        }
    </style>
</head>
<body>

<h2>Reporte de Vehículos Más Rentados y Ganancias</h2>

<table>
    <tr>
        <th>Vehículo</th>
        <th>Marca</th>
        <th>Veces Rentado</th>
        <th>Ganancias Generadas ($)</th>
    </tr>

    <?php while($fila = $datos->fetch_assoc()) { ?>

    <tr>
        <td><?= $fila['car_name'] ?></td>
        <td><?= $fila['marca'] ?></td>
        <td><?= $fila['veces_rentado'] ?></td>
        <td><?= number_format($fila['ganancias'],2) ?></td>
    </tr>

    <?php } ?>

</table>

</body>
</html>
