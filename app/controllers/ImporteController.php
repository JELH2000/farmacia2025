<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/ImporteModel.php";

$importeModel = new ImporteModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "agregar":
            $vencimiento = isset($_POST["vencimiento"]) ? trim($_POST["vencimiento"]) : null;
            $fechaCompra = isset($_POST["fechaCompra"]) ? trim($_POST["fechaCompra"]) : null;
            $codigoProducto = isset($_POST["codigoProducto"]) ? (int)$_POST["codigoProducto"] : 0;
            $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "Disponible";
            $fkProducto = isset($_POST["fkProducto"]) ? (int)$_POST["fkProducto"] : 0;
            $fkProveedor = isset($_POST["fkProveedor"]) ? (int)$_POST["fkProveedor"] : 0;

            if (empty($vencimiento) || empty($fechaCompra) || $codigoProducto <= 0 || $fkProducto <= 0 || $fkProveedor <= 0) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($importeModel->existeCodigo($codigoProducto)) {
                $response = ["status" => "error", "message" => "El código del producto ya existe"];
                break;
            }

            // Validar fechas
            if (strtotime($vencimiento) <= strtotime($fechaCompra)) {
                $response = ["status" => "error", "message" => "La fecha de vencimiento debe ser posterior a la fecha de compra"];
                break;
            }

            if ($importeModel->registrar($vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor)) {
                $response = ["status" => "success", "message" => "Importación registrada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo registrar la importación"];
            }
            break;

        case "listar":
            $datos = $importeModel->getImportes();
            $data = array();

            foreach ($datos as $row) {
                $id = $row["idImporte"];

                $sub_array = array();
                $sub_array[] = htmlspecialchars($row["idImporte"]);
                $sub_array[] = htmlspecialchars($row["ProductoNombre"] ?? 'N/A');
                $sub_array[] = htmlspecialchars($row["ProveedorNombre"] ?? 'N/A');
                $sub_array[] = htmlspecialchars($row["CodigoProducto"]);
                $sub_array[] = htmlspecialchars($row["FechaCompra"]);
                $sub_array[] = htmlspecialchars($row["Vencimiento"]);
                
                // Color según estado
                $estadoClass = '';
                switch($row["Estado"]) {
                    case 'Disponible': $estadoClass = 'badge bg-success'; break;
                    case 'Agotado': $estadoClass = 'badge bg-warning'; break;
                    case 'Vencido': $estadoClass = 'badge bg-danger'; break;
                    case 'Dañado': $estadoClass = 'badge bg-secondary'; break;
                    default: $estadoClass = 'badge bg-info';
                }
                $sub_array[] = '<span class="' . $estadoClass . '">' . htmlspecialchars($row["Estado"]) . '</span>';
                
                $buttons = '<center>
                <div class="dropdown">
                    <button class="btn btn-light" style="padding-top:0 !important; padding-bottom:0 !important;" 
                        type="button" id="dropdownMenuButton_' . $id . '" 
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_' . $id . '">
                        <button class="dropdown-item" onClick="editar(' . $id . ');">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Editar
                        </button>
                        <button class="dropdown-item text-danger" onClick="eliminar(' . $id . ');">
                            <i class="fa-solid fa-trash me-2"></i>Eliminar
                        </button>
                    </div>
                </div>
            </center>';

                $sub_array[] = $buttons;
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );

            $response = $results;
            break;

        case "mostrar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            $importe = $importeModel->getImporteById($id);

            if ($importe) {
                $response = ["status" => "success", "data" => $importe];
            } else {
                $response = ["status" => "error", "message" => "Importación no encontrada"];
            }
            break;

        case "productos":
            $productos = $importeModel->getProductos();
            $response = ["status" => "success", "data" => $productos];
            break;

        case "proveedores":
            $proveedores = $importeModel->getProveedores();
            $response = ["status" => "success", "data" => $proveedores];
            break;

        case "editar":
            $id = isset($_POST["idImporte"]) ? (int)$_POST["idImporte"] : 0;
            $vencimiento = isset($_POST["vencimiento"]) ? trim($_POST["vencimiento"]) : null;
            $fechaCompra = isset($_POST["fechaCompra"]) ? trim($_POST["fechaCompra"]) : null;
            $codigoProducto = isset($_POST["codigoProducto"]) ? (int)$_POST["codigoProducto"] : 0;
            $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "Disponible";
            $fkProducto = isset($_POST["fkProducto"]) ? (int)$_POST["fkProducto"] : 0;
            $fkProveedor = isset($_POST["fkProveedor"]) ? (int)$_POST["fkProveedor"] : 0;

            if (empty($id) || empty($vencimiento) || empty($fechaCompra) || $codigoProducto <= 0 || $fkProducto <= 0 || $fkProveedor <= 0) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($importeModel->existeCodigo($codigoProducto, $id)) {
                $response = ["status" => "error", "message" => "El código del producto ya existe"];
                break;
            }

            // Validar fechas
            if (strtotime($vencimiento) <= strtotime($fechaCompra)) {
                $response = ["status" => "error", "message" => "La fecha de vencimiento debe ser posterior a la fecha de compra"];
                break;
            }

            if ($importeModel->actualizar($id, $vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor)) {
                $response = ["status" => "success", "message" => "Importación actualizada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo actualizar la importación"];
            }
            break;

        case "eliminar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            if ($importeModel->eliminar($id)) {
                $response = ["status" => "success", "message" => "Importación eliminada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo eliminar la importación"];
            }
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en ImporteController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
?>