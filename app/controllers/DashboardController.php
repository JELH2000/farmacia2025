<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/DashboardModel.php";

$dashboardModel = new DashboardModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "estadisticas":
            $estadisticas = $dashboardModel->getEstadisticasGenerales();
            $response = ["status" => "success", "data" => $estadisticas];
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en DashboardController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
?>