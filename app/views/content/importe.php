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
            <h1>Importaciones</h1>
            <nav class="header-nav ms-auto" style="margin-top: 3px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="inicio" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item">Inventario</li>
                    <li class="breadcrumb-item active">Importaciones</li>
                    <input type="hidden" id="paginaActual" name="paginaActual" value="Importaciones">
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
                                <i class="bx bx-plus"></i> Nueva Importación
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
                    <table id="tablaImporte" class="table table-striped nowrap table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr class="p-3 mb-2 bg-secondary text-white text-center">
                                <th class="text-center">ID</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Proveedor</th>
                                <th class="text-center">Código</th>
                                <th class="text-center">Fecha Compra</th>
                                <th class="text-center">Vencimiento</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center notexport">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Crear/Editar -->
            <div id="modalImporte" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="tituloModal"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post" id="formImporte" autocomplete="off">
                            <div class="modal-body py-3 px-3">
                                <input type="hidden" id="idImporte" name="idImporte">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="fkProducto" name="fkProducto" required>
                                                <option value="">Seleccione un producto</option>
                                            </select>
                                            <label for="fkProducto">Producto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="fkProveedor" name="fkProveedor" required>
                                                <option value="">Seleccione un proveedor</option>
                                            </select>
                                            <label for="fkProveedor">Proveedor</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="codigoProducto" name="codigoProducto" type="number" placeholder=" " required />
                                            <label for="codigoProducto">Código Producto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-control" id="estado" name="estado" required>
                                                <option value="Disponible">Disponible</option>
                                                <option value="Agotado">Agotado</option>
                                                <option value="Vencido">Vencido</option>
                                                <option value="Dañado">Dañado</option>
                                            </select>
                                            <label for="estado">Estado</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="fechaCompra" name="fechaCompra" type="date" required />
                                            <label for="fechaCompra">Fecha de Compra</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="vencimiento" name="vencimiento" type="date" required />
                                            <label for="vencimiento">Fecha de Vencimiento</label>
                                        </div>
                                    </div>
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
    
    <script>
    $(document).ready(function () {
        // Cargar datos iniciales
        cargarProductos();
        cargarProveedores();

        // Inicializar DataTable
        var table = $('#tablaImporte').DataTable({
            ajax: {
                url: 'app/controllers/ImporteController.php',
                type: "POST",
                data: { opcion: "listar" },
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            language: {
                url: "app/ajax/idioma.json"
            },
            "aaSorting": [],
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "Todos"]],
            pageLength: 5,
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { orderable: false, targets: -1 },
                { searchable: false, targets: -1 }
            ],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        // Botones de exportación
        $('#btnPdf, #btnExcel, #btnPrint').on('click', function() {
            Swal.fire({
                title: 'Funcionalidad en desarrollo',
                text: 'La exportación estará disponible pronto',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        });

        function cargarProductos() {
            $.ajax({
                url: "app/controllers/ImporteController.php",
                type: "POST",
                dataType: "json",
                data: { opcion: "productos" },
                success: function (response) {
                    if (response.status === "success") {
                        var select = $('#fkProducto');
                        select.empty();
                        select.append('<option value="">Seleccione un producto</option>');
                        
                        response.data.forEach(function(producto) {
                            select.append('<option value="' + producto.idProducto + '">' + 
                                         producto.Nombre + '</option>');
                        });
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function cargarProveedores() {
            $.ajax({
                url: "app/controllers/ImporteController.php",
                type: "POST",
                dataType: "json",
                data: { opcion: "proveedores" },
                success: function (response) {
                    if (response.status === "success") {
                        var select = $('#fkProveedor');
                        select.empty();
                        select.append('<option value="">Seleccione un proveedor</option>');
                        
                        response.data.forEach(function(proveedor) {
                            select.append('<option value="' + proveedor.idProveedor + '">' + 
                                         proveedor.Nombre + '</option>');
                        });
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $("#nuevo").on("click", function(){
            // Fecha actual para fecha de compra
            var today = new Date().toISOString().split('T')[0];
            $('#fechaCompra').val(today);

            $('#idImporte').val("");
            $('#codigoProducto').val("");
            $('#estado').val("Disponible");
            $('#vencimiento').val("");

            $('#tituloModal').html('NUEVA IMPORTACIÓN');
            $(".accion").attr("id","guardar");
            $('#modalImporte').modal('show');
        });

        $("#formImporte").on("submit", function (e) {
            e.preventDefault();
            let accion = $(".accion").attr("id");

            let productoVal = $("#fkProducto").val();
            let proveedorVal = $("#fkProveedor").val();
            let codigoVal = $("#codigoProducto").val();
            let fechaCompraVal = $("#fechaCompra").val();
            let vencimientoVal = $("#vencimiento").val();
            let estadoVal = $("#estado").val();

            if(!isValidProducto(productoVal)){
                mostrarError("Por favor, seleccione un producto.");
                return;
            }

            if(!isValidProveedor(proveedorVal)){
                mostrarError("Por favor, seleccione un proveedor.");
                return;
            }

            if(!isValidCodigo(codigoVal)){
                mostrarError("Por favor, ingrese un código válido.");
                return;
            }

            if(!isValidFecha(fechaCompraVal)){
                mostrarError("Por favor, seleccione una fecha de compra válida.");
                return;
            }

            if(!isValidFecha(vencimientoVal)){
                mostrarError("Por favor, seleccione una fecha de vencimiento válida.");
                return;
            }

            // Valida que la fecha de vencimiento sea posterior a la de compra
            if(new Date(vencimientoVal) <= new Date(fechaCompraVal)) {
                mostrarError("La fecha de vencimiento debe ser posterior a la fecha de compra.");
                return;
            }

            guardaryeditar(e, accion);
        });

        function isValidProducto(producto) {
            return producto !== "";
        }

        function isValidProveedor(proveedor) {
            return proveedor !== "";
        }

        function isValidCodigo(codigo) {
            return codigo > 0;
        }

        function isValidFecha(fecha) {
            return fecha !== "";
        }

        function guardaryeditar(e, accion) {
            e.preventDefault();
            let formData = new FormData($("#formImporte")[0]);
            formData.append("opcion", (accion === "guardar") ? "agregar" : "editar");

            $.ajax({
                url: "app/controllers/ImporteController.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    $("#modalImporte").modal('hide');
                    if (response.status === "success") {
                        $('#formImporte')[0].reset();
                        $('#tablaImporte').DataTable().ajax.reload(null, false);
                        mostrarToast("success", response.message);
                    } else {
                        mostrarError(response.message);
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    mostrarError("Error en el servidor. Intente más tarde.");
                }
            });
        }

        function editar(id) {
            $.ajax({
                url: "app/controllers/ImporteController.php",
                type: "POST",
                dataType: "json",
                data: { 
                    opcion: "mostrar",
                    id: id 
                },
                success: function (data) {
                    if (data.status === "error") {
                        return mostrarError(data.message);
                    }

                    const importe = data.data;
                    $('#idImporte').val(importe.idImporte);
                    $('#fkProducto').val(importe.fkProducto);
                    $('#fkProveedor').val(importe.fkProveedor);
                    $('#codigoProducto').val(importe.CodigoProducto);
                    $('#estado').val(importe.Estado);
                    $('#fechaCompra').val(importe.FechaCompra);
                    $('#vencimiento').val(importe.Vencimiento);

                    $('#tituloModal').html('EDITAR IMPORTACIÓN');
                    $(".accion").attr("id", "editar");
                    $('#modalImporte').modal('show');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    mostrarError("Error en el servidor.");
                }
            });
        }

        function eliminar(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar esta importación?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "app/controllers/ImporteController.php",
                        type: "POST",
                        dataType: "json",
                        data: { 
                            opcion: "eliminar",
                            id: id 
                        },
                        success: function (response) {
                            if (response.status === "success") {
                                $('#tablaImporte').DataTable().ajax.reload(null, false);
                                mostrarToast("success", response.message);
                            } else {
                                mostrarError(response.message);
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            mostrarError("Error en el servidor.");
                        }
                    });
                }
            });
        }

        function mostrarError(mensaje) {
            Swal.fire({
                title: "ERROR",
                text: mensaje,
                icon: "error",
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false
            });
        }

        function mostrarToast(icono, mensaje) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({ icon: icono, title: mensaje });
        }

        //las use por que el host nomedejava usar las normales y solo de esta forma me dejo 
        //y por el tinto ya no los puse aparte por que genarava unerror que desia que el scrip estab abajo del codigo
        window.editar = editar;
        window.eliminar = eliminar;
        window.mostrarError = mostrarError;
        window.mostrarToast = mostrarToast;
    });
    </script>
</body>
</html>