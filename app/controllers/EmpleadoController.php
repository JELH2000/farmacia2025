<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/EmpleadoModel.php";

$empleadoModel = new EmpleadoModel();
$response = ["status" => "error", "message" => "Solicitud inválida"];

try {
    // Obtener opción desde POST
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;

    switch ($opcion) {
        case "agregar":
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : null;
            $contrasenia = isset($_POST["contrasenia"]) ? trim($_POST["contrasenia"]) : null;
            $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "Activo";

            if (empty($nombre) || empty($usuario) || empty($contrasenia)) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($empleadoModel->existeUsuario($usuario)) {
                $response = ["status" => "error", "message" => "El nombre de usuario ya existe"];
                break;
            }

            if ($empleadoModel->registrar($nombre, $usuario, $contrasenia, $estado)) {
                $response = ["status" => "success", "message" => "Empleado agregado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo agregar el empleado"];
            }
            break;

        case "listar":
            // CORRECCIÓN: Usar getAllEmpleados en lugar de getEmpleados
            $datos = $empleadoModel->getAllEmpleados();
            $data = array();

            foreach ($datos as $row) {
                $id = $row["idEmpleado"];

                $sub_array = array();
                $sub_array[] = htmlspecialchars($row["idEmpleado"]);
                $sub_array[] = htmlspecialchars($row["Nombre"]);
                $sub_array[] = htmlspecialchars($row["Usuario"]);
                $sub_array[] = htmlspecialchars($row["Estado"]);

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

            $empleado = $empleadoModel->getEmpleadoById($id);

            if ($empleado) {
                $response = ["status" => "success", "data" => $empleado];
            } else {
                $response = ["status" => "error", "message" => "Empleado no encontrado"];
            }
            break;

        case "editar":
            $id = isset($_POST["idEmpleado"]) ? (int)$_POST["idEmpleado"] : 0;
            $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
            $usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : null;
            $contrasenia = isset($_POST["contrasenia"]) ? trim($_POST["contrasenia"]) : null;
            $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "Activo";

            if (empty($id) || empty($nombre) || empty($usuario) || empty($contrasenia)) {
                $response = ["status" => "error", "message" => "Todos los campos son obligatorios"];
                break;
            }

            if ($empleadoModel->existeUsuario($usuario, $id)) {
                $response = ["status" => "error", "message" => "El nombre de usuario ya existe"];
                break;
            }

            if ($empleadoModel->actualizar($id, $nombre, $usuario, $contrasenia, $estado)) {
                $response = ["status" => "success", "message" => "Empleado actualizado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo actualizar el empleado"];
            }
            break;

        case "eliminar":
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            if ($id <= 0) {
                $response = ["status" => "error", "message" => "ID inválido"];
                break;
            }

            // Verificar si el empleado está siendo usado
            if ($empleadoModel->estaEnUso($id)) {
                $response = ["status" => "error", "message" => "No se puede eliminar el empleado porque tiene ventas asociadas"];
                break;
            }

            if ($empleadoModel->eliminar($id)) {
                $response = ["status" => "success", "message" => "Empleado eliminado exitosamente"];
            } else {
                $response = ["status" => "error", "message" => "No se pudo eliminar el empleado"];
            }
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en EmpleadoController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
