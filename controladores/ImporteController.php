<?php

require_once MODELOS . '/ImporteModel.php';

class ImporteController
{
    private $model;
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, "farmacia");
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
        $this->model = new ImporteModel($this->conn);
    }

    public function index()
    {
        $action = $_GET['action'] ?? 'listar';
        $importe = null;
        $mensaje = '';
        $tipoMensaje = '';
        $importes = [];
        $terminoBusqueda = '';
        $productos = $this->model->obtenerProductos();
        $proveedores = $this->model->obtenerProveedores();

        // acciones POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            switch ($action) {
                case 'crear':
                    try {
                        $result = $this->model->insertar(
                            $_POST['vencimiento'],
                            $_POST['fechaCompra'],
                            $_POST['codigoProducto'],
                            $_POST['estado'],
                            $_POST['fkProducto'],
                            $_POST['fkProveedor']
                        );
                        if ($result) {
                            $mensaje = "Importe creado correctamente.";
                            $tipoMensaje = "success";
                            $action = 'listar';
                        }
                    } catch (Exception $e) {
                        $mensaje = "Error: " . $e->getMessage();
                        $tipoMensaje = "danger";
                    }
                    break;

                case 'actualizar':
                    try {
                        $result = $this->model->actualizar(
                            $_POST['id'],
                            $_POST['vencimiento'],
                            $_POST['fechaCompra'],
                            $_POST['codigoProducto'],
                            $_POST['estado'],
                            $_POST['fkProducto'],
                            $_POST['fkProveedor']
                        );
                        if ($result) {
                            $mensaje = "Importe actualizado correctamente.";
                            $tipoMensaje = "success";
                            $action = 'listar';
                        }
                    } catch (Exception $e) {
                        $mensaje = "Error: " . $e->getMessage();
                        $tipoMensaje = "danger";
                    }
                    break;

                case 'eliminar':
                    try {
                        $result = $this->model->eliminar($_POST['id']);
                        if ($result) {
                            $mensaje = "Importe eliminado correctamente.";
                            $tipoMensaje = "success";
                            $action = 'listar';
                        }
                    } catch (Exception $e) {
                        $mensaje = "Error: " . $e->getMessage();
                        $tipoMensaje = "danger";
                    }
                    break;
            }
        }

        // Cargar datos 
        switch ($action) {
            case 'editar':
                if (isset($_GET['id'])) {
                    $importe = $this->model->obtenerPorId($_GET['id']);
                    $action = 'actualizar';
                }
                break;

            case 'buscar':
                $terminoBusqueda = $_GET['buscar'] ?? '';
                $importes = !empty($terminoBusqueda) ? $this->model->buscar($terminoBusqueda) : $this->model->listar();
                break;

            case 'listar':
            default:
                $importes = $this->model->listar();
                break;
        }

        // Cargar la vista
        $this->cargarVista([
            'action' => $action,
            'importes' => $importes,
            'importe' => $importe,
            'productos' => $productos,
            'proveedores' => $proveedores,
            'mensaje' => $mensaje,
            'tipoMensaje' => $tipoMensaje,
            'terminoBusqueda' => $terminoBusqueda
        ]);
    }

    private function cargarVista($datos = [])
    {
        extract($datos);
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <title>Gestión de Importes</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .card {
                    margin-bottom: 20px;
                }

                .btn-action {
                    margin: 2px;
                }
            </style>
        </head>

        <body class="bg-light">
            <div class="container mt-4">
                <!-- Header -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <?php
                                $titulos = [
                                    'listar' => 'Lista de Importes',
                                    'crear' => 'Crear Nuevo Importe',
                                    'actualizar' => 'Actualizar Importe',
                                    'buscar' => 'Resultados de Búsqueda'
                                ];
                                echo $titulos[$action] ?? 'Gestión de Importes';
                                ?>
                            </h4>
                            <div>
                                <?php if ($action !== 'crear'): ?>
                                    <a href="?action=crear" class="btn btn-success btn-sm">Nuevo Importe</a>
                                <?php endif; ?>
                                <?php if ($action !== 'listar'): ?>
                                    <a href="?" class="btn btn-secondary btn-sm">Volver al Listado</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <script>
                        setTimeout(function() {
                            const alert = document.querySelector('.alert');
                            if (alert) {
                                const bsAlert = new bootstrap.Alert(alert);
                                bsAlert.close();
                            }
                        }, 5000);
                    </script>
                <?php endif; ?>


                <?php if (in_array($action, ['listar', 'buscar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <form method="GET" action="" class="row g-3 align-items-end">
                                <input type="hidden" name="action" value="buscar">
                                <div class="col-md-8">
                                    <label for="buscar" class="form-label">Buscar Importes:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar"
                                        placeholder="Buscar por código, estado, producto o proveedor..."
                                        value="<?php echo htmlspecialchars($terminoBusqueda); ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                    <a href="?" class="btn btn-secondary">Limpiar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>


                <?php if (in_array($action, ['crear', 'actualizar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if ($action === 'crear' || $action === 'actualizar'): ?>
                                <!-- crear o actualizar -->
                                <form method="POST" action="?action=<?php echo $action; ?>">
                                    <?php if ($action === 'actualizar' && isset($importe)): ?>
                                        <input type="hidden" name="id" value="<?php echo $importe['idImporte']; ?>">
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vencimiento" class="form-label">Fecha Vencimiento:</label>
                                                <input type="date" class="form-control" id="vencimiento" name="vencimiento"
                                                    value="<?php echo isset($importe) ? $importe['Vencimiento'] : ''; ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fechaCompra" class="form-label">Fecha Compra:</label>
                                                <input type="date" class="form-control" id="fechaCompra" name="fechaCompra"
                                                    value="<?php echo isset($importe) ? $importe['FechaCompra'] : date('Y-m-d'); ?>"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="codigoProducto" class="form-label">Código Producto:</label>
                                                <input type="number" class="form-control" id="codigoProducto" name="codigoProducto"
                                                    value="<?php echo isset($importe) ? $importe['CodigoProducto'] : ''; ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="estado" class="form-label">Estado:</label>
                                                <select class="form-control" id="estado" name="estado" required>
                                                    <option value="">Seleccionar estado</option>
                                                    <option value="Disponible" <?php echo (isset($importe) && $importe['Estado'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                                                    <option value="Agotado" <?php echo (isset($importe) && $importe['Estado'] == 'Agotado') ? 'selected' : ''; ?>>Agotado</option>
                                                    <option value="Vencido" <?php echo (isset($importe) && $importe['Estado'] == 'Vencido') ? 'selected' : ''; ?>>Vencido</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fkProducto" class="form-label">Producto:</label>
                                                <select class="form-control" id="fkProducto" name="fkProducto" required>
                                                    <option value="">Seleccionar producto</option>
                                                    <?php foreach ($productos as $producto): ?>
                                                        <option value="<?php echo $producto['idProducto']; ?>"
                                                            <?php echo (isset($importe) && $importe['fkProducto'] == $producto['idProducto']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($producto['Nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="fkProveedor" class="form-label">Proveedor:</label>
                                                <select class="form-control" id="fkProveedor" name="fkProveedor" required>
                                                    <option value="">Seleccionar proveedor</option>
                                                    <?php foreach ($proveedores as $proveedor): ?>
                                                        <option value="<?php echo $proveedor['idProveedor']; ?>"
                                                            <?php echo (isset($importe) && $importe['fkProveedor'] == $proveedor['idProveedor']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($proveedor['Nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-<?php echo $action === 'crear' ? 'success' : 'primary'; ?>">
                                            <?php echo $action === 'crear' ? 'Crear Importe' : 'Actualizar Importe'; ?>
                                        </button>
                                        <a href="?" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tabla -->
                <?php if (in_array($action, ['listar', 'buscar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if (count($importes) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Vencimiento</th>
                                                <th>Compra</th>
                                                <th>Código</th>
                                                <th>Estado</th>
                                                <th>Producto</th>
                                                <th>Proveedor</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($importes as $imp): ?>
                                                <tr>
                                                    <td><?php echo $imp['idImporte']; ?></td>
                                                    <td><?php echo $imp['Vencimiento']; ?></td>
                                                    <td><?php echo $imp['FechaCompra']; ?></td>
                                                    <td><?php echo $imp['CodigoProducto']; ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php
                                                                                echo $imp['Estado'] == 'Disponible' ? 'success' : ($imp['Estado'] == 'Agotado' ? 'warning' : 'danger');
                                                                                ?>">
                                                            <?php echo $imp['Estado']; ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($imp['producto_nombre'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($imp['proveedor_nombre'] ?? 'N/A'); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="?action=editar&id=<?php echo $imp['idImporte']; ?>"
                                                                class="btn btn-warning btn-sm btn-action">Editar</a>
                                                            <form method="POST" action="?action=eliminar" style="display: inline;">
                                                                <input type="hidden" name="id" value="<?php echo $imp['idImporte']; ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm btn-action"
                                                                    onclick="return confirm('¿Está seguro de eliminar este importe?')">
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <?php if (!empty($terminoBusqueda)): ?>
                                        No se encontraron importes que coincidan con: "<?php echo htmlspecialchars($terminoBusqueda); ?>"
                                    <?php else: ?>
                                        No hay importes registrados.
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>
<?php
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>