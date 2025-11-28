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
            <h1>Productos</h1>
            <nav class="header-nav ms-auto" style="margin-top: 3px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="inicio" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item">Inventario</li>
                    <li class="breadcrumb-item active">Productos</li>
                    <input type="hidden" id="paginaActual" name="paginaActual" value="Productos">
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
                                <i class="bx bx-plus"></i> Nuevo Producto
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
                    <table id="tablaProducto" class="table table-striped nowrap table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr class="p-3 mb-2 bg-secondary text-white text-center">
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">Categoría</th>
                                <th class="text-center notexport">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Crear/Editar -->
            <div id="modalProducto" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="tituloModal"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post" id="formProducto" autocomplete="off">
                            <div class="modal-body py-3 px-3">
                                <input type="hidden" id="idProducto" name="idProducto">
                                
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder=" " maxlength="100" required />
                                    <span class="mensaje-validacion">El nombre debe tener entre 2 y 100 caracteres.</span>
                                    <label for="nombre">Nombre del Producto</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="precio" name="precio" type="number" step="0.01" min="0" placeholder=" " required />
                                    <span class="mensaje-validacion">El precio debe ser mayor a 0.</span>
                                    <label for="precio">Precio ($)</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="descripcion" name="descripcion" placeholder=" " style="height: 100px" maxlength="500"></textarea>
                                    <span class="mensaje-validacion">La descripción no debe exceder los 500 caracteres.</span>
                                    <label for="descripcion">Descripción</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select class="form-control" id="fkCategoria" name="fkCategoria" required>
                                        <option value="">Seleccione una categoría</option>
                                        <!-- Las categorías se cargarán dinámicamente -->
                                    </select>
                                    <label for="fkCategoria">Categoría</label>
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
        // Cargar categorías al iniciar
        cargarCategorias();

        // Inicializar DataTable
        var table = $('#tablaProducto').DataTable({
            ajax: {
                url: 'app/controllers/ProductoController.php',
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

        function cargarCategorias() {
            $.ajax({
                url: "app/controllers/ProductoController.php",
                type: "POST",
                dataType: "json",
                data: { opcion: "categorias" },
                success: function (response) {
                    if (response.status === "success") {
                        var select = $('#fkCategoria');
                        select.empty();
                        select.append('<option value="">Seleccione una categoría</option>');
                        
                        response.data.forEach(function(categoria) {
                            select.append('<option value="' + categoria.idCategoria + '">' + 
                                         categoria.Nombre + '</option>');
                        });
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $("#nuevo").on("click", function(){
            $('#idProducto').val("");
            $('#nombre').val("");
            $('#precio').val("");
            $('#descripcion').val("");
            $('#fkCategoria').val("");

            $('#nombre').addClass('resetear');
            $('#precio').addClass('resetear');
            $('#descripcion').addClass('resetear');
            $('.mensaje-validacion').removeClass('mostrar');

            $('#tituloModal').html('NUEVO PRODUCTO');
            $(".accion").attr("id","guardar");
            $('#modalProducto').modal('show');
        });

        $("#formProducto").on("submit", function (e) {
            e.preventDefault();
            let accion = $(".accion").attr("id");

            let nombreVal = limpiarInput($("#nombre").val());
            let precioVal = $("#precio").val();
            let descripcionVal = limpiarInput($("#descripcion").val());
            let categoriaVal = $("#fkCategoria").val();

            if(!isValidNombre(nombreVal)){
                mostrarError("Por favor, ingrese un nombre válido (2-100 caracteres).");
                return;
            }

            if(!isValidPrecio(precioVal)){
                mostrarError("El precio debe ser mayor a 0.");
                return;
            }

            if(!isValidDescripcion(descripcionVal)){
                mostrarError("La descripción no debe exceder los 500 caracteres.");
                return;
            }

            if(!isValidCategoria(categoriaVal)){
                mostrarError("Por favor, seleccione una categoría.");
                return;
            }

            guardaryeditar(e, accion);
        });

        var nombre = $("#nombre");
        var precio = $("#precio");
        var descripcion = $("#descripcion");

        nombre.on("input", function() {
            this.value = limpiarInput(this.value);
            crearListener(isValidNombre)(event);
        });

        precio.on("input", function() {
            crearListenerPrecio(isValidPrecio)(event);
        });

        descripcion.on("input", function() {
            this.value = limpiarInput(this.value);
            crearListenerDescripcion(isValidDescripcion)(event);
        });

        function crearListener(validator) {
            return e => {
                const input = e.target;
                const text = e.target.value;
                const valid = validator(text);
                const showTip = text !== "" && !valid;
                const toolTip = e.target.nextElementSibling;
                showOrHideTio(showTip, toolTip, input);
            };
        }

        function crearListenerPrecio(validator) {
            return e => {
                const input = e.target;
                const text = e.target.value;
                const valid = validator(text);
                const showTip = text !== "" && !valid;
                const toolTip = e.target.nextElementSibling;
                showOrHideTio(showTip, toolTip, input);
            };
        }

        function crearListenerDescripcion(validator) {
            return e => {
                const input = e.target;
                const text = e.target.value;
                const valid = validator(text);
                const showTip = text !== "" && !valid;
                const toolTip = e.target.nextElementSibling;
                showOrHideTio(showTip, toolTip, input);
            };
        }

        function showOrHideTio(show, element, input) {
            if (show) {
                element.classList.add('mostrar');
                input.classList.remove('resetear');
                input.style.border = "1px solid red";
            } else {
                input.style.border = "1px solid #DEE2E6";
                element.classList.remove('mostrar');
            }
        }

        function isValidNombre(nombre) {
            return /^[a-zA-Z\u00C0-\u017F0-9\s\-]{2,100}$/.test(nombre);
        }

        function isValidPrecio(precio) {
            return precio > 0;
        }

        function isValidDescripcion(descripcion) {
            return descripcion.length <= 500;
        }

        function isValidCategoria(categoria) {
            return categoria !== "";
        }

        function limpiarInput(text) {
            return text.replace(/^\s+/, '').replace(/\s\s+/g, ' ');
        }

        function guardaryeditar(e, accion) {
            e.preventDefault();
            let formData = new FormData($("#formProducto")[0]);
            formData.append("opcion", (accion === "guardar") ? "agregar" : "editar");

            $.ajax({
                url: "app/controllers/ProductoController.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    $("#modalProducto").modal('hide');
                    if (response.status === "success") {
                        $('#formProducto')[0].reset();
                        $('#tablaProducto').DataTable().ajax.reload(null, false);
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
                url: "app/controllers/ProductoController.php",
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

                    const producto = data.data;
                    $('#idProducto').val(producto.idProducto);
                    $('#nombre').val(producto.Nombre);
                    $('#precio').val(producto.Precio);
                    $('#descripcion').val(producto.Descripcion || '');
                    $('#fkCategoria').val(producto.fkCategoria);

                    $('#nombre').addClass('resetear');
                    $('#precio').addClass('resetear');
                    $('#descripcion').addClass('resetear');
                    $('.mensaje-validacion').removeClass('mostrar');

                    $('#tituloModal').html('EDITAR PRODUCTO');
                    $(".accion").attr("id", "editar");
                    $('#modalProducto').modal('show');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    mostrarError("Error en el servidor.");
                }
            });
        }

        function eliminar(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar este producto?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "app/controllers/ProductoController.php",
                        type: "POST",
                        dataType: "json",
                        data: { 
                            opcion: "eliminar",
                            id: id 
                        },
                        success: function (response) {
                            if (response.status === "success") {
                                $('#tablaProducto').DataTable().ajax.reload(null, false);
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

        // funciones globales
        window.editar = editar;
        window.eliminar = eliminar;
        window.mostrarError = mostrarError;
        window.mostrarToast = mostrarToast;
    });
    </script>
</body>
</html>