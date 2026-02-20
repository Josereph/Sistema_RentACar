<?php
if(!isset($_GET['id_reserva'])){
    echo "Reserva no especificada";
    exit;
}

$id_reserva = $_GET['id_reserva'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Confirmado</title>
    <style>
        body{
            font-family: Arial;
            text-align:center;
            padding-top:80px;
            background:#f4f4f4;
        }
        .card{
            background:white;
            width:400px;
            margin:auto;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }
        .btn{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            background:#007BFF;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }
        .btn:hover{
            background:#0056b3;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>✅ Pago realizado con éxito</h2>
    <p>Su contrato de renta ha sido generado correctamente.</p>

    <a class="btn"
       href="verContrato.php?id_reserva=<?= $id_reserva ?>"
       target="_blank">
       Ver Contrato
    </a>
</div>

</body>
</html>
