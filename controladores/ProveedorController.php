<?php
require_once MODELOS . '/ProveedorModel.php';

class ProveedorController
{
    private $model;
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, "farmacia");
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
        $this->model = new ProveedorModel($this->conn);
    }

    public function index()
    {
        $action = $_GET['action'] ?? 'listar';
        $proveedor = null;
        $mensaje = '';
        $tipoMensaje = '';
        $proveedores = [];
        $terminoBusqueda = '';

        // Procesar acciones POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            switch ($action) {
                case 'crear':
                    try {
                        $result = $this->model->insertar(
                            $_POST['nombre'],
                            $_POST['telefono'],
                            $_POST['direccion']
                        );
                        if ($result) {
                            $mensaje = "Proveedor creado correctamente.";
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
                            $_POST['nombre'],
                            $_POST['telefono'],
                            $_POST['direccion']
                        );
                        if ($result) {
                            $mensaje = "Proveedor actualizado correctamente.";
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
                            $mensaje = "Proveedor eliminado correctamente.";
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

        
        switch ($action) {
            case 'editar':
                if (isset($_GET['id'])) {
                    $proveedor = $this->model->obtenerPorId($_GET['id']);
                    $action = 'actualizar';
                }
                break;

            case 'buscar':
                $terminoBusqueda = $_GET['buscar'] ?? '';
                $proveedores = !empty($terminoBusqueda) ? $this->model->buscar($terminoBusqueda) : $this->model->listar();
                break;

            case 'listar':
            default:
                $proveedores = $this->model->listar();
                break;
        }

        // Cargar la vista
        $this->cargarVista([
            'action' => $action,
            'proveedores' => $proveedores,
            'proveedor' => $proveedor,
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
            <title>Gestión de Proveedores</title>
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
                                    'listar' => 'Lista de Proveedores',
                                    'crear' => 'Crear Nuevo Proveedor',
                                    'actualizar' => 'Actualizar Proveedor',
                                    'buscar' => 'Resultados de Búsqueda'
                                ];
                                echo $titulos[$action] ?? 'Gestión de Proveedores';
                                ?>
                            </h4>
                            <div>
                                <?php if ($action !== 'crear'): ?>
                                    <a href="?action=crear" class="btn btn-success btn-sm">Nuevo Proveedor</a>
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

                <!-- Formulario de buscar -->
                <?php if (in_array($action, ['listar', 'buscar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <form method="GET" action="" class="row g-3 align-items-end">
                                <input type="hidden" name="action" value="buscar">
                                <div class="col-md-8">
                                    <label for="buscar" class="form-label">Buscar Proveedores:</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar"
                                        placeholder="Buscar por nombre, teléfono o dirección..."
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

                <!-- Formularios de crud -->
                <?php if (in_array($action, ['crear', 'actualizar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if ($action === 'crear' || $action === 'actualizar'): ?>
                                <form method="POST" action="?action=<?php echo $action; ?>">
                                    <?php if ($action === 'actualizar' && isset($proveedor)): ?>
                                        <input type="hidden" name="id" value="<?php echo $proveedor['idProveedor']; ?>">
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre"
                                                    value="<?php echo isset($proveedor) ? htmlspecialchars($proveedor['Nombre']) : ''; ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">Teléfono:</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono"
                                                    value="<?php echo isset($proveedor) ? htmlspecialchars($proveedor['Telefono']) : ''; ?>"
                                                    required maxlength="9"
                                                    pattern="\d{4}-\d{4}"
                                                    placeholder="1234-5678"
                                                    title="Ingrese 8 dígitos en formato: 1234-5678">
                                                <div class="form-text">Formato: 1234-5678 (8 dígitos con guión)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección:</label>
                                        <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php
                                            echo isset($proveedor) ? htmlspecialchars($proveedor['Direccion']) : '';
                                        ?></textarea>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-<?php echo $action === 'crear' ? 'success' : 'primary'; ?>">
                                            <?php echo $action === 'crear' ? 'Crear Proveedor' : 'Actualizar Proveedor'; ?>
                                        </button>
                                        <a href="?" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- tabla -->
                <?php if (in_array($action, ['listar', 'buscar'])): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <?php if (count($proveedores) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proveedores as $prov): ?>
                                                <tr>
                                                    <td><?php echo $prov['idProveedor']; ?></td>
                                                    <td><?php echo htmlspecialchars($prov['Nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($prov['Telefono']); ?></td>
                                                    <td><?php echo htmlspecialchars($prov['Direccion'] ?? 'No especificada'); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="?action=editar&id=<?php echo $prov['idProveedor']; ?>"
                                                                class="btn btn-warning btn-sm btn-action">Editar</a>
                                                            <form method="POST" action="?action=eliminar" style="display: inline;">
                                                                <input type="hidden" name="id" value="<?php echo $prov['idProveedor']; ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm btn-action" 
                                                                    onclick="return confirm('¿Está seguro de eliminar a <?php echo htmlspecialchars($prov['Nombre']); ?>?')">
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
                                        No se encontraron proveedores que coincidan con: "<?php echo htmlspecialchars($terminoBusqueda); ?>"
                                    <?php else: ?>
                                        No hay proveedores registrados.
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