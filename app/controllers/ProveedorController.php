<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/ProveedorModel.php";

$proveedorModel = new ProveedorModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "agregar":
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
            $direccion = isset($_POST["direccion"]) ? trim($_POST["direccion"]) : null;

            if (empty($nombre) || empty($telefono) || empty($direccion)) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($proveedorModel->existeNombre($nombre)) {
                $response = ["status" => "error", "message" => "El nombre del proveedor ya existe"];
                break;
            }

            if ($proveedorModel->registrar($nombre, $telefono, $direccion)) {
                $response = ["status" => "success", "message" => "Proveedor agregado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo agregar el proveedor"];
            }
            break;

        case "listar":
            $datos = $proveedorModel->getProveedores();
            $data = array();

            foreach ($datos as $row) {
                $id = $row["idProveedor"];

                $sub_array = array();
                $sub_array[] = htmlspecialchars($row["idProveedor"]);
                $sub_array[] = htmlspecialchars($row["Nombre"]);
                $sub_array[] = htmlspecialchars($row["Telefono"]);
                $sub_array[] = htmlspecialchars($row["Direccion"] ?? '');
                
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

            $proveedor = $proveedorModel->getProveedorById($id);

            if ($proveedor) {
                $response = ["status" => "success", "data" => $proveedor];
            } else {
                $response = ["status" => "error", "message" => "Proveedor no encontrado"];
            }
            break;

        case "editar":
            $id = isset($_POST["idProveedor"]) ? (int)$_POST["idProveedor"] : 0;
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
            $direccion = isset($_POST["direccion"]) ? trim($_POST["direccion"]) : null;

            if (empty($id) || empty($nombre) || empty($telefono) || empty($direccion)) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($proveedorModel->existeNombre($nombre, $id)) {
                $response = ["status" => "error", "message" => "El nombre del proveedor ya existe"];
                break;
            }

            if ($proveedorModel->actualizar($id, $nombre, $telefono, $direccion)) {
                $response = ["status" => "success", "message" => "Proveedor actualizado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo actualizar el proveedor"];
            }
            break;

        case "eliminar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            // Verificar si el proveedor está siendo usado
            if ($proveedorModel->estaEnUso($id)) {
                $response = ["status" => "error", "message" => "No se puede eliminar el proveedor porque tiene importes asociados"];
                break;
            }

            if ($proveedorModel->eliminar($id)) {
                $response = ["status" => "success", "message" => "Proveedor eliminado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo eliminar el proveedor"];
            }
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en ProveedorController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
?>