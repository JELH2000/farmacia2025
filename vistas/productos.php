<?php
ob_start();
require_once VISTAS . '/productoAction/listarProductos.html';
$content = ob_get_clean();
$page_title = "Productos";
require_once DIRROOT . '/layout.php';
