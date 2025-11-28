<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}
ob_start();
require_once VISTAS . '/categoriaAction/listarCategorias.html';
$content = ob_get_clean();
$page_title = "Categorias";
require_once DIRROOT . '/layout.php';
