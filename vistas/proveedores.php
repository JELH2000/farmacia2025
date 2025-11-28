<?php
// vistas/proveedores.php
if (!defined('CONFIG')) {
    require_once '../config.php';
}

// Incluir el controlador
require_once CONTROLADORES . '/ProveedorController.php';

// Obtener el contenido del controlador
ob_start();
$controller = new ProveedorController();
$controller->index();
$content = ob_get_clean();

// Definir título de página
$page_title = "Gestión de Proveedores";

// Incluir el layout
include 'layout.php';
?>