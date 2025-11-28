<?php
// vistas/importes.php
if (!defined('CONFIG')) {
    require_once '../config.php';
}

// Incluir el controlador
require_once CONTROLADORES . '/ImporteController.php';

// Obtener el contenido del controlador
ob_start();
$controller = new ImporteController();
$controller->index();
$content = ob_get_clean();

// Definir título de página
$page_title = "Gestión de Importes";

// Incluir el layout
include 'layout.php';
?>