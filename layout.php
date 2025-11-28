<?php
// vistas/layout.php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $page_title ?? 'Inicio'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .navbar-brand {
            font-weight: bold;
        }

        .main-content {
            padding: 20px 0;
            min-height: calc(100vh - 76px);
        }

        .dropdown-menu {
            border-radius: 0.375rem;
        }

        .active {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Incluir el navbar -->
    <?php
    $navbar_path = VISTAS . '/navbar.php';
    if (file_exists($navbar_path)) {
        require_once $navbar_path;
    } else {
        // Navbar simple de emergencia
        echo '
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="' . generateUrl('home.php') . '">🏥 ' . APP_NAME . '</a>
                <span class="navbar-text text-warning">Navbar no encontrado</span>
            </div>
        </nav>';
    }
    ?>

    <!-- Contenido principal -->
    <div class="container-fluid main-content">
        <div class="container">
            <?php echo $content ?? ''; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>