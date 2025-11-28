<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION["empleado"]) || empty($_SESSION["empleado"])) {
    header("Location: index.php");
    exit;
}

$empleado = $_SESSION["empleado"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="ri-hospital-line"></i> Farmacia - Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="ri-user-3-line"></i> <?php echo htmlspecialchars($empleado['Nombre']); ?>
                </span>
                <button class="btn btn-outline-light btn-sm" onclick="cerrarSesion()">
                    <i class="ri-logout-box-r-line"></i> Salir
                </button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="ri-checkbox-circle-line"></i> ¡Login Exitoso!</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>Bienvenido al sistema</h5>
                            <p class="mb-0">Has iniciado sesión correctamente en Farmacia.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="ri-id-card-line"></i> ID Empleado
                                        </h6>
                                        <p class="card-text h4"><?php echo $empleado['idEmpleado']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            <i class="ri-user-line"></i> Usuario
                                        </h6>
                                        <p class="card-text h4"><?php echo htmlspecialchars($empleado['Usuario']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">
                                            <i class="ri-account-box-line"></i> Nombre
                                        </h6>
                                        <p class="card-text h4"><?php echo htmlspecialchars($empleado['Nombre']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="ri-check-line"></i> Estado
                                        </h6>
                                        <p class="card-text h4"><?php echo $empleado['Estado']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function cerrarSesion() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: "¿Estás seguro de que deseas salir del sistema?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'app/controllers/AuthController.php',
                    type: 'POST',
                    data: {
                        opcion: 'cerrar'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = response.redirect || 'index.php';
                        }
                    },
                    error: function() {
                        window.location.href = 'index.php';
                    }
                });
            }
        });
    }
    </script>
</body>
</html>