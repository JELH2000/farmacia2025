<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/CategoriaModel.php";

$categoriaModel = new CategoriaModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "agregar":
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";

            if (empty($nombre)) {
                $response = ["status" => "error", "message" => "El nombre es obligatorio"];
                break;
            }

            if ($categoriaModel->existeNombre($nombre)) {
                $response = ["status" => "error", "message" => "El nombre de la categoría ya existe"];
                break;
            }

            if ($categoriaModel->registrar($nombre, $descripcion)) {
                $response = ["status" => "success", "message" => "Categoría agregada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo agregar la categoría"];
            }
            break;

        case "listar":
            $datos = $categoriaModel->getCategorias();
            $data = array();

            foreach ($datos as $row) {
                $id = $row["idCategoria"];

                $sub_array = array();
                $sub_array[] = htmlspecialchars($row["idCategoria"]);
                $sub_array[] = htmlspecialchars($row["Nombre"]);
                $sub_array[] = htmlspecialchars($row["Descripcion"] ?? '');
                
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

            $categoria = $categoriaModel->getCategoriaById($id);

            if ($categoria) {
                $response = ["status" => "success", "data" => $categoria];
            } else {
                $response = ["status" => "error", "message" => "Categoría no encontrada"];
            }
            break;

        case "editar":
            $id = isset($_POST["idCategoria"]) ? (int)$_POST["idCategoria"] : 0;
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";

            if (empty($id) || empty($nombre)) {
                $response = ["status" => "error", "message" => "El nombre es obligatorio"];
                break;
            }

            if ($categoriaModel->existeNombre($nombre, $id)) {
                $response = ["status" => "error", "message" => "El nombre de la categoría ya existe"];
                break;
            }

            if ($categoriaModel->actualizar($id, $nombre, $descripcion)) {
                $response = ["status" => "success", "message" => "Categoría actualizada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo actualizar la categoría"];
            }
            break;

        case "eliminar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            // Verificar si la categoría está siendo usada
            if ($categoriaModel->estaEnUso($id)) {
                $response = ["status" => "error", "message" => "No se puede eliminar la categoría porque tiene productos asociados"];
                break;
            }

            if ($categoriaModel->eliminar($id)) {
                $response = ["status" => "success", "message" => "Categoría eliminada exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo eliminar la categoría"];
            }
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en CategoriaController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
?>