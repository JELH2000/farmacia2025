<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}
ob_start();
require_once VISTAS . '/productoAction/listarProductos.html';
$content = ob_get_clean();
$page_title = "Productos";
require_once DIRROOT . '/layout.php';
