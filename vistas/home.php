<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}
// vistas/home.php
ob_start();
?>
<div class="row">
    <div class="col-12 text-center mb-5">
        <h1 class="display-4">Bienvenido a <?php echo APP_NAME; ?></h1>
        <p class="lead">Sistema integral de gestión para farmacias - v<?php echo APP_VERSION; ?></p>
    </div>
</div>

<div class="row">
    <?php foreach (MODULOS as $modulo => $config): ?>
        <?php if ($modulo !== 'dashboard'): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi <?php echo $config['icono']; ?> fs-1 text-primary mb-3"></i>
                        <h5 class="card-title"><?php echo $config['nombre']; ?></h5>
                        <p class="card-text">Gestiona tus <?php echo strtolower($config['nombre']); ?></p>
                        <div class="btn-group w-100">
                            <a href="<?php echo generateUrl($config['archivo']); ?>" class="btn btn-outline-primary">
                                Ver Lista
                            </a>
                            <a href="<?php echo generateUrl($config['archivo'] . '?action=crear'); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="row mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <?php
                    $acciones_rapidas = ['proveedores', 'importes', 'productos', 'ventas'];
                    foreach ($acciones_rapidas as $accion):
                        if (isset(MODULOS[$accion])):
                    ?>
                            <div class="col-md-6">
                                <a href="<?php echo generateUrl(MODULOS[$accion]['archivo'] . '?action=crear'); ?>"
                                    class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi <?php echo MODULOS[$accion]['icono']; ?> me-2"></i>
                                    Nuevo <?php echo rtrim(MODULOS[$accion]['nombre'], 's'); ?>
                                </a>
                            </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estadísticas</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Proveedores
                        <span class="badge bg-primary rounded-pill">25</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Productos activos
                        <span class="badge bg-success rounded-pill">150</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Ventas hoy
                        <span class="badge bg-warning rounded-pill">45</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Importes pendientes
                        <span class="badge bg-info rounded-pill">12</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$page_title = "Inicio";
require_once dirname(__DIR__) . 'layout.php';
?>