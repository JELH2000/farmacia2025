<?php
require_once "./config/app.php";
require_once "./autoload.php";

session_start();


if(!isset($_SESSION['empleado']) && !isset($_SESSION['usuario'])){
    header("Location: login");
    exit();
}

$viewsController = new app\controllers\viewsController();
$vista = $viewsController->obtenerVistasControlador('importe');

if($vista == "app/views/content/404.php"){
    require_once "app/views/content/importe.php";
} else {
    require_once $vista;
}
?>