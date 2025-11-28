<!DOCTYPE html>
<html lang="es">

<head>
    <?php include 'app/views/inc/head.php'; ?>

</head>

<body class="toggle-sidebar" onload="loaded();">

    <!-- ======= Header ======= -->
    <?php include 'app/views/inc/header.php'; ?>
    <!-- End Header -->

    <main id="main" class="main">
        <div class="pagetitle d-flex" style="margin-top: -8px;">
            <h1>Categorías</h1>
            <nav class="header-nav ms-auto" style="margin-top: 3px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="inicio" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item">Inventario</li>
                    <li class="breadcrumb-item active">Categorías</li>
                    <input type="hidden" id="paginaActual" name="paginaActual" value="Categorías">
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div style="width: 100%; margin-left: -5px; margin-right: -5px;">
                <!-- Botones -->
                <div class="container-fluid mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <a type="button" class="btn btn-primary" id="nuevo">
                                <i class="bx bx-plus"></i> Nueva Categoría
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger" id="btnPdf">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-success" id="btnExcel">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-info" id="btnPrint">
                                    <i class="fa fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="padding: 10px 10px 10px 10px;">
                    <table id="tablaCategoria" class="table table-striped nowrap table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr class="p-3 mb-2 bg-secondary text-white text-center">
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center notexport">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Crear/Editar -->
            <div id="modalCategoria" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="tituloModal"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post" id="formCategoria" autocomplete="off">
                            <div class="modal-body py-3 px-3">
                                <input type="hidden" id="idCategoria" name="idCategoria">

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder=" " maxlength="100" required />
                                    <span class="mensaje-validacion">El nombre debe tener entre 2 y 100 caracteres.</span>
                                    <label for="nombre">Nombre de la Categoría</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="descripcion" name="descripcion" placeholder=" " style="height: 100px" maxlength="500"></textarea>
                                    <span class="mensaje-validacion">La descripción no debe exceder los 500 caracteres.</span>
                                    <label for="descripcion">Descripción</label>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="accion" id="guardar" value="Guardar" class="btn btn-primary accion">Guardar</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

        </section>
    </main>

    <!-- ===== Sidebar ===== -->
    <?php include 'app/views/inc/sidebar.php'; ?>
    <!-- End Sidebar-->

    <!-- ======= Footer ======= -->
    <?php include 'app/views/inc/footer.php'; ?>
    <!-- End Footer-->

    <?php include 'app/views/inc/script.php'; ?>
    <script src="app/ajax/categoria.js"></script>
</body>

</html>