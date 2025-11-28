<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/ProductoModel.php";

$productoModel = new ProductoModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "agregar":
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $precio = isset($_POST["precio"]) ? (float)$_POST["precio"] : 0;
            $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";
            $fkCategoria = isset($_POST["fkCategoria"]) ? (int)$_POST["fkCategoria"] : 0;

            if (empty($nombre) || $precio <= 0 || $fkCategoria <= 0) {
                $response = ["status" => "error", "message" => "Nombre, precio y categoría son obligatorios"];
                break;
            }

            if ($productoModel->existeNombre($nombre)) {
                $response = ["status" => "error", "message" => "El nombre del producto ya existe"];
                break;
            }

            if ($productoModel->registrar($nombre, $precio, $descripcion, $fkCategoria)) {
                $response = ["status" => "success", "message" => "Producto agregado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo agregar el producto"];
            }
            break;

        case "listar":
            $datos = $productoModel->getProductos();
            $data = array();

            foreach ($datos as $row) {
                $id = $row["idProducto"];

                $sub_array = array();
                $sub_array[] = htmlspecialchars($row["idProducto"]);
                $sub_array[] = htmlspecialchars($row["Nombre"]);
                $sub_array[] = '$' . number_format($row["Precio"], 2);
                $sub_array[] = htmlspecialchars($row["Descripcion"] ?? '');
                $sub_array[] = htmlspecialchars($row["fkCategoria"]);
                
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

            $producto = $productoModel->getProductoById($id);

            if ($producto) {
                $response = ["status" => "success", "data" => $producto];
            } else {
                $response = ["status" => "error", "message" => "Producto no encontrado"];
            }
            break;

        case "categorias":
            $categorias = $productoModel->getCategorias();
            $response = ["status" => "success", "data" => $categorias];
            break;

        case "editar":
            $id = isset($_POST["idProducto"]) ? (int)$_POST["idProducto"] : 0;
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $precio = isset($_POST["precio"]) ? (float)$_POST["precio"] : 0;
            $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : "";
            $fkCategoria = isset($_POST["fkCategoria"]) ? (int)$_POST["fkCategoria"] : 0;

            if (empty($id) || empty($nombre) || $precio <= 0 || $fkCategoria <= 0) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($productoModel->existeNombre($nombre, $id)) {
                $response = ["status" => "error", "message" => "El nombre del producto ya existe"];
                break;
            }

            if ($productoModel->actualizar($id, $nombre, $precio, $descripcion, $fkCategoria)) {
                $response = ["status" => "success", "message" => "Producto actualizado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo actualizar el producto"];
            }
            break;

        case "eliminar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            // Verificar si el producto está siendo usado
            if ($productoModel->estaEnUso($id)) {
                $response = ["status" => "error", "message" => "No se puede eliminar el producto porque tiene importes o ventas asociadas"];
                break;
            }

            if ($productoModel->eliminar($id)) {
                $response = ["status" => "success", "message" => "Producto eliminado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo eliminar el producto"];
            }
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en ProductoController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
?>