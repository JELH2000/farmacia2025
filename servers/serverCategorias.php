<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}

header('Content-Type: application/json');
if (isset($_POST['option'])) {
    switch ($_POST['option']) {
        case "crear":
            $controller = new categoriaController();
            try {
                $controller->agregarCategoria($_POST['nombre'], $_POST['descripcion']);
                $data = array("status" => "exito", "message" => "Se creo una categoria con exito.");
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "allCategori":
            $controller = new categoriaController();
            try {
                $lista = $controller->obtenerCategorias();
                $elementos = [];
                foreach ($lista as $elemento) {
                    $elementos[count($elementos)] = array(
                        "id" => $elemento->getIdCategoria(),
                        "nombre" => $elemento->getNombre(),
                        "descripcion" => $elemento->getDescripcion()
                    );
                }
                $data = array(
                    "status" => "exito",
                    "message" => "Se creo una categoria con exito.",
                    "datos" => $elementos
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "eliminar":
            $controller = new categoriaController();
            try {
                $controller->eliminarCategoria(intval($_POST['id']));
                $data = array(
                    "status" => "exito",
                    "message" => "Se creo una categoria con exito."
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "buscarCategoria":
            $controller = new categoriaController();
            try {
                $lista = $controller->buscarCategoria($_POST['palabra']);
                $elementos = [];
                foreach ($lista as $elemento) {
                    $elementos[count($elementos)] = array(
                        "id" => $elemento->getIdCategoria(),
                        "nombre" => $elemento->getNombre(),
                        "descripcion" => $elemento->getDescripcion()
                    );
                }
                $data = array(
                    "status" => "exito",
                    "message" => "Se creo una categoria con exito.",
                    "datos" => $elementos
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "actualizar":
            $controller = new categoriaController();
            try {
                $controller->actualizarCategoria(intval($_POST['id']), (intval($_POST['nombreStatus']) == 1 ? $_POST['nombre'] : null), $_POST['descripcion']);
                $data = array("status" => "exito", "message" => "Se actualizo una categoria con exito.");
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        default:
            $data = array("status" => "error", "message" => "La opcion no esta disponible.");
            echo json_encode($data);
            exit();
            break;
    }
} else {
    $data = array("status" => "error", "message" => "No se a encontrado la opcion solicitada.");
    echo json_encode($data);
    exit();
}
