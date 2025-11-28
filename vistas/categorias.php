<?php
ob_start();
require_once VISTAS . '/categoriaAction/listarCategorias.html';
$content = ob_get_clean();
$page_title = "Categorias";
require_once DIRROOT . '/layout.php';
