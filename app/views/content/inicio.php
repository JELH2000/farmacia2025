<!DOCTYPE html>
<html lang="es">

<head>
    <?php include 'app/views/inc/head.php'; ?>
    <style>
        .card-title {
            font-size: 16px;
        }

        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Estilos para las tarjetas de estadísticas */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
        }
        
        .stats-card .card-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
        
        .stats-card .card-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }
        
        .stats-card .card-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <?php include 'app/views/inc/header.php'; ?>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include 'app/views/inc/sidebar.php'; ?>
        <!-- ========== Left Sidebar End ========== -->

        <main id="main" class="main">
            <div class="container-fluid py-3">
                
                <!-- Sección de Estadísticas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="text-center mb-3">Estadísticas del Sistema</h4>
                    </div>
                    
                    <!-- Total Productos -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card shadow">
                            <div class="card-body text-center py-4">
                                <div class="card-icon mb-2">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <h3 class="card-value" id="totalProductos">0</h3>
                                <p class="card-label">Total Productos</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ventas Hoy -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card shadow" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-center py-4">
                                <div class="card-icon mb-2">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h3 class="card-value" id="ventasHoy">$0</h3>
                                <p class="card-label">Ventas Hoy</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Productos Disponibles -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card shadow" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-center py-4">
                                <div class="card-icon mb-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="card-value" id="productosDisponibles">0</h3>
                                <p class="card-label">Disponibles</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Productos Agotados -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card stats-card shadow" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-center py-4">
                                <div class="card-icon mb-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h3 class="card-value" id="productosAgotados">0</h3>
                                <p class="card-label">Agotados</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Menú Principal (Tu diseño original) -->
                <div class="text-center py-3">
                    <h4>Bienvenido. Elija una opción</h4>
                    <p style="font-size: 16px;">Recuerde que siempre puede explorar el menú completo haciendo click en la parte superior izquierda</p>
                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3">

                        <div class="col">
                            <a class="card h-100 shadow-sm d-flex align-items-center justify-content-center py-2 hover-card" href="categoria" style="text-decoration:none;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                    <h6 class="card-title text-dark mt-2 text-center">Categoria</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a class="card h-100 shadow-sm d-flex align-items-center justify-content-center py-2 hover-card" href="empleado" style="text-decoration:none;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                    <h6 class="card-title text-dark mt-2 text-center">Empleado</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a class="card h-100 shadow-sm d-flex align-items-center justify-content-center py-2 hover-card" href="proveedor" style="text-decoration:none;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                    <h6 class="card-title text-dark mt-2 text-center">Proveedor</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a class="card h-100 shadow-sm d-flex align-items-center justify-content-center py-2 hover-card" href="importe" style="text-decoration:none;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                    <h6 class="card-title text-dark mt-2 text-center">Importe</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a class="card h-100 shadow-sm d-flex align-items-center justify-content-center py-2 hover-card" href="producto" style="text-decoration:none;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                    <h6 class="card-title text-dark mt-2 text-center">Producto</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ======= Footer ======= -->
        <?php include 'app/views/inc/footer.php'; ?>
        <!-- ======= End Footer ======= -->

    </div>

    <?php include 'app/views/inc/script.php'; ?>
    
    <script>
    $(document).ready(function () {
        // Cargar estadísticas al iniciar
        cargarEstadisticas();

        // Actualizar cada 30 segundos
        setInterval(function() {
            cargarEstadisticas();
        }, 30000);

        function cargarEstadisticas() {
            $.ajax({
                url: "app/controllers/DashboardController.php",
                type: "POST",
                dataType: "json",
                data: { opcion: "estadisticas" },
                success: function (response) {
                    if (response.status === "success") {
                        $('#totalProductos').text(response.data.totalProductos);
                        $('#ventasHoy').text('$' + parseFloat(response.data.ventasHoy).toFixed(2));
                        $('#productosDisponibles').text(response.data.productosDisponibles);
                        $('#productosAgotados').text(response.data.productosAgotados);
                    }
                },
                error: function (xhr) {
                    console.error('Error cargando estadísticas:', xhr.responseText);
                }
            });
        }
    });
    </script>
</body>
</html>