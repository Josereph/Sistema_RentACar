<?php

/* ========= RUTA DEL LOGO ========= */
$logo = __DIR__ . '/../../assets/img/logo.png';

/* ========= HTML DEL CONTRATO ========= */
$html = '

<style>
    body{
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.6;
    }

    .header{
        text-align:center;
        margin-bottom:20px;
    }

    .empresa{
        font-size:16px;
        font-weight:bold;
    }

    .titulo{
        text-align:center;
        font-size:20px;
        font-weight:bold;
        margin-bottom:20px;
    }

    .subtitulo{
        font-size:14px;
        font-weight:bold;
        margin-top:20px;
        margin-bottom:10px;
    }

    .info{
        margin-bottom:10px;
    }

    .declaracion{
        margin-top:25px;
        text-align:justify;
        border:1px solid #000;
        padding:10px;
    }

    .firmas{
        margin-top:50px;
        width:100%;
    }

    .firma{
        width:45%;
        display:inline-block;
        text-align:center;
    }

    .linea{
        margin-top:60px;
        border-top:1px solid black;
        width:80%;
        margin-left:auto;
        margin-right:auto;
    }
</style>

<div class="header">
    <img src="'.$logo.'" width="150"><br>
    <div class="empresa">RENT A CAR EL SALVADOR S.A DE C.V</div>
</div>

<hr>

<div class="titulo">
    CONTRATO DE ARRENDAMIENTO DE VEHÍCULO
</div>

<div class="info">
<strong>Número de Contrato:</strong> '.$numero_contrato.'<br>
<strong>Fecha:</strong> '.date("d/m/Y").'
</div>

<div class="subtitulo">DATOS DEL CLIENTE</div>
<div class="info">
<strong>Nombre:</strong> '.$datos["nombre"].' '.$datos["apellido"].'<br>
<strong>DUI:</strong> '.$datos["DUI"].'
</div>

<div class="subtitulo">DATOS DEL VEHÍCULO</div>
<div class="info">
<strong>Vehículo:</strong> '.$datos["marca"].' '.$datos["car_name"].'<br>
<strong>Modelo:</strong> '.$datos["modelo"].'<br>
<strong>Placa:</strong> '.$datos["numero_placa"].'
</div>

<div class="subtitulo">DETALLES DE LA RENTA</div>
<div class="info">
<strong>Fecha de Recogida:</strong> '.$datos["fecha_recogida"].'<br>
<strong>Fecha de Entrega:</strong> '.$datos["fecha_entrega"].'<br>
<strong>Precio Total:</strong> $'.$datos["precio_total"].'
</div>


<div class="subtitulo">DECLARACIÓN Y OBLIGACIONES DEL ARRENDATARIO</div>
<div class="declaracion">
El arrendatario declara haber recibido el vehículo en condiciones óptimas de funcionamiento y se compromete de manera expresa e irrevocable a devolverlo dentro del plazo estipulado en el presente contrato. Asimismo, acepta que cualquier retraso en la devolución, daño ocasionado al vehículo, o incumplimiento de las condiciones aquí establecidas, podrá generar cargos adicionales, penalidades o sanciones conforme a las políticas internas del arrendador. Este documento constituye una obligación contractual formal entre las partes.
</div>

<div class="firmas">
    <div class="firma">
        <div class="linea"></div>
        Firma del Cliente
    </div>

    <div class="firma">
        <div class="linea"></div>
        Firma del Arrendador
    </div>
</div>
';

?>

