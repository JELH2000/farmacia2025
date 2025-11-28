<?php

require_once "./config/app.php";
require_once "./autoload.php";

use app\controllers\viewsController;

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ["login"];
}

session_start();

$viewsController = new viewsController();
$vista = $viewsController->obtenerVistasControlador($url[0]);

if (empty($_SESSION['usuario'])) {
    if ($vista != "app/views/content/404.php") {
        $vista = "app/views/content/login.php"; //se pone la pagina de inico
    }
    require_once $vista;
} else {
    if ($vista == "app/views/content/login.php") {
        $vista = "app/views/content/cerrar.php";
    }
    require_once $vista;
}

