<?php

function enviarCorreo($para, $asunto, $mensaje) {

    $headers = "From: no-reply@GoCarRentAcar.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($para, $asunto, $mensaje, $headers);
}
