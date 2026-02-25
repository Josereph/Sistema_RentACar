<?php
require_once __DIR__ . '/helpers/EmailHelper.php';
$resultado = EmailHelper::send('josephorell05@gmail.com', 'Prueba', '<h1>Hola</h1>', 'Texto plano');
var_dump($resultado);