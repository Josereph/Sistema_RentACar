<?php
class BaseEmpleadoController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true || $_SESSION['admin_rol'] !== 'operador') {
            header('Location: ' . url('index.php?controller=Auth&action=login'));
            exit;
        }
    }
}