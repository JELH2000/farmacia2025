<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}
require_once MODELOS . '/categoria.php';
require_once MODELOS . '/producto.php';
require_once CONTROLADORES . '/CategoriaController.php';
require_once CONTROLADORES . '/ProductoController.php';



if (isset($_POST['option'])) {
    switch ($_POST['option']) {
        case "crear":
            header('Content-Type: application/json');
            try {
                $fileTmpName = $_FILES['Foto']['tmp_name'];
                $fileSize = $_FILES['Foto']['size'];
                $fileError = $_FILES['Foto']['error'];
                if ($fileError != 0 || $fileSize == 0) {
                    throw new Exception("Error al subir el archivo o archivo vacío.");
                }

                $imgContent = file_get_contents($fileTmpName);
                $controller = new productoController();
                $controller->agregarProducto($_POST['Nombre'], floatVal($_POST['Precio']), $_POST['Descripcion'], $imgContent, intval($_POST['Categoria']));
                $data = array(
                    "status" => "exito",
                    "message" => "Se creo un producto con exito.",
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "allProducto":
            header('Content-Type: application/json');
            $controller = new productoController();
            try {
                $lista = $controller->obtenerProductos();
                $elementos = [];
                foreach ($lista as $elemento) {
                    $elementos[count($elementos)] = array(
                        "id" => $elemento->getIdProducto(),
                        "Nombre" => $elemento->getNombre(),
                        "Precio" => $elemento->getPrecio(),
                        "Descripcion" => $elemento->getDescripcion(),
                        "Categoria" => $elemento->getCategoria()->getIdCategoria()
                    );
                }
                $data = array(
                    "status" => "exito",
                    "message" => "Se extrajeron los datos.",
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
        case "fotoPoridProducto":
            $controller = new productoController();
            try {
                $producto = $controller->buscarProductoPorId(intval($_POST['id']));
                header("Content-Type: image/jpeg");
                echo $producto->getFoto();
                exit();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
            break;
        case "listCategoria":
            header('Content-Type: application/json');
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
                    "message" => "Se extrajeron los datos.",
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
            $controller = new productoController();
            try {
                $controller->eliminarProducto(intval($_POST['id']));
                $data = array(
                    "status" => "exito",
                    "message" => "Se elimino el producto.",
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        case "buscar":
            $controller = new productoController();
            try {
                $lista = $controller->buscarProducto($_POST['buscar']);
                $elementos = [];
                foreach ($lista as $elemento) {
                    $elementos[count($elementos)] = array(
                        "id" => $elemento->getIdProducto(),
                        "Nombre" => $elemento->getNombre(),
                        "Precio" => $elemento->getPrecio(),
                        "Descripcion" => $elemento->getDescripcion(),
                        "Categoria" => $elemento->getCategoria()->getIdCategoria()
                    );
                }
                $data = array(
                    "status" => "exito",
                    "message" => "Se extrajeron los datos.",
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
            $controller = new productoController();
            try {
                $Nombre = (intval($_POST['nom']) == 1 ? $_POST['Nombre'] : null);
                $archivo = (intval($_POST['archivo']) == 1 ? $_FILES['Foto']['tmp_name'] : null);


                $imgContent = ($archivo != null ? file_get_contents($archivo) : null);
                $controller = new productoController();
                $controller->actualizarProducto($_POST['id'], $Nombre, floatVal($_POST['Precio']), $_POST['Descripcion'], $imgContent, intval($_POST['Categoria']));
                $data = array(
                    "status" => "exito",
                    "message" => "Se actualizo un producto.",
                );
                echo json_encode($data);
                exit();
            } catch (Exception $e) {
                $data = array("status" => "error", "message" => $e->getMessage());
                echo json_encode($data);
                exit();
            }
            break;
        default:
            header('Content-Type: application/json');
            $data = array("status" => "error", "message" => "La opcion no esta disponible.");
            echo json_encode($data);
            exit();
            break;
    }
} else {
    header('Content-Type: application/json');
    $data = array("status" => "error", "message" => "No se a encontrado la opcion solicitada.");
    echo json_encode($data);
    exit();
}
exit();
