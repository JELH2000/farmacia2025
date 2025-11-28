<?php
// vistas/navbar.php
$current_url = $_GET['url'] ?? HOMEPAGE;
$pagina_actual = basename($current_url);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo generateUrl('home.php'); ?>">
            🏥 <?php echo APP_NAME; ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach (MODULOS as $modulo => $config): ?>
                    <?php if ($modulo === 'dashboard'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isActivePage($pagina_actual, $config['archivo']) ? 'active' : ''; ?>"
                                href="<?php echo generateUrl($config['archivo']); ?>">
                                <i class="bi <?php echo $config['icono']; ?> me-1"></i>
                                <?php echo $config['nombre']; ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo (strpos($pagina_actual, $modulo) !== false) ? 'active' : ''; ?>"
                                href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi <?php echo $config['icono']; ?> me-1"></i>
                                <?php echo $config['nombre']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item"
                                        href="<?php echo generateUrl($config['archivo']); ?>">
                                        Lista de <?php echo $config['nombre']; ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                        href="<?php echo generateUrl($config['archivo'] . '?action=crear'); ?>">
                                        Nuevo <?php echo rtrim($config['nombre'], 's'); ?>
                                    </a>
                                </li>
                                <?php if ($modulo === 'productos'): ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="<?php echo generateUrl('categorias.php'); ?>">
                                            Gestionar Categorías
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        Usuario
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text">
                                <small>Sesión activa</small>
                            </span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Cerrar Sesión
                            </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>