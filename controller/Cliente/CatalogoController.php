<?php
// controller/Cliente/CatalogoController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CatalogoController
{
    public function index()
    {
        // Redirigir a la vista de catálogo existente
        require_once __DIR__ . '/../../views/ReservaCatalogo/views/catalogo.php';
    }
}