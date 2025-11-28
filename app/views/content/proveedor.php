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
            <h1>Proveedores</h1>
            <nav class="header-nav ms-auto" style="margin-top: 3px;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="inicio" class="text-decoration-none">Inicio</a></li>
                    <li class="breadcrumb-item">Inventario</li>
                    <li class="breadcrumb-item active">Proveedores</li>
                    <input type="hidden" id="paginaActual" name="paginaActual" value="Proveedores">
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
                                <i class="bx bx-plus"></i> Nuevo Proveedor
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
                    <table id="tablaProveedor" class="table table-striped nowrap table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr class="p-3 mb-2 bg-secondary text-white text-center">
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Dirección</th>
                                <th class="text-center notexport">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Crear/Editar -->
            <div id="modalProveedor" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="tituloModal"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="post" id="formProveedor" autocomplete="off">
                            <div class="modal-body py-3 px-3">
                                <input type="hidden" id="idProveedor" name="idProveedor">
                                
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder=" " maxlength="100" required />
                                    <span class="mensaje-validacion">El nombre debe tener entre 2 y 100 caracteres.</span>
                                    <label for="nombre">Nombre del Proveedor</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="telefono" name="telefono" type="text" placeholder=" " maxlength="12" required />
                                    <span class="mensaje-validacion">El teléfono debe tener entre 8 y 12 caracteres.</span>
                                    <label for="telefono">Teléfono</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="direccion" name="direccion" placeholder=" " style="height: 100px" maxlength="255" required></textarea>
                                    <span class="mensaje-validacion">La dirección no debe exceder los 255 caracteres.</span>
                                    <label for="direccion">Dirección</label>
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
    // el codigoesta aqui para evitar errorres de carga
    $(document).ready(function () {
        
        var table = $('#tablaProveedor').DataTable({
            ajax: {
                url: 'app/controllers/ProveedorController.php',
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

        
        $('#btnPdf, #btnExcel, #btnPrint').on('click', function() {
            Swal.fire({
                title: 'Funcionalidad en desarrollo',
                text: 'La exportación estará disponible pronto',
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        });

        $("#nuevo").on("click", function(){
            $('#idProveedor').val("");
            $('#nombre').val("");
            $('#telefono').val("");
            $('#direccion').val("");

            $('#nombre').addClass('resetear');
            $('#telefono').addClass('resetear');
            $('#direccion').addClass('resetear');
            $('.mensaje-validacion').removeClass('mostrar');

            $('#tituloModal').html('NUEVO PROVEEDOR');
            $(".accion").attr("id","guardar");
            $('#modalProveedor').modal('show');
        });

        $("#formProveedor").on("submit", function (e) {
            e.preventDefault();
            let accion = $(".accion").attr("id");

            let nombreVal = limpiarInput($("#nombre").val());
            let telefonoVal = limpiarInput($("#telefono").val());
            let direccionVal = limpiarInput($("#direccion").val());

            if(!isValidNombre(nombreVal)){
                mostrarError("Por favor, ingrese un nombre válido (2-100 caracteres).");
                return;
            }

            if(!isValidTelefono(telefonoVal)){
                mostrarError("Por favor, ingrese un teléfono válido (8-12 caracteres).");
                return;
            }

            if(!isValidDireccion(direccionVal)){
                mostrarError("La dirección no debe exceder los 255 caracteres.");
                return;
            }

            guardaryeditar(e, accion);
        });

        var nombre = $("#nombre");
        var telefono = $("#telefono");
        var direccion = $("#direccion");

        nombre.on("input", function() {
            this.value = limpiarInput(this.value);
            crearListener(isValidNombre)(event);
        });

        telefono.on("input", function() {
            this.value = limpiarTelefono(this.value);
            crearListenerTelefono(isValidTelefono)(event);
        });

        direccion.on("input", function() {
            this.value = limpiarInput(this.value);
            crearListenerDireccion(isValidDireccion)(event);
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

        function crearListenerTelefono(validator) {
            return e => {
                const input = e.target;
                const text = e.target.value;
                const valid = validator(text);
                const showTip = text !== "" && !valid;
                const toolTip = e.target.nextElementSibling;
                showOrHideTio(showTip, toolTip, input);
            };
        }

        function crearListenerDireccion(validator) {
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
            return /^[a-zA-Z\u00C0-\u017F0-9\s\-\&\.]{2,100}$/.test(nombre);
        }

        function isValidTelefono(telefono) {
            return /^[\d\s\-\(\)]{8,12}$/.test(telefono);
        }

        function isValidDireccion(direccion) {
            return direccion.length >= 5 && direccion.length <= 255;
        }

        function limpiarInput(text) {
            return text.replace(/^\s+/, '').replace(/\s\s+/g, ' ');
        }

        function limpiarTelefono(text) {
            return text.replace(/[^\d\s\-\(\)]/g, '');
        }

        function guardaryeditar(e, accion) {
            e.preventDefault();
            let formData = new FormData($("#formProveedor")[0]);
            formData.append("opcion", (accion === "guardar") ? "agregar" : "editar");

            $.ajax({
                url: "app/controllers/ProveedorController.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    $("#modalProveedor").modal('hide');
                    if (response.status === "success") {
                        $('#formProveedor')[0].reset();
                        $('#tablaProveedor').DataTable().ajax.reload(null, false);
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
                url: "app/controllers/ProveedorController.php",
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

                    const proveedor = data.data;
                    $('#idProveedor').val(proveedor.idProveedor);
                    $('#nombre').val(proveedor.Nombre);
                    $('#telefono').val(proveedor.Telefono);
                    $('#direccion').val(proveedor.Direccion || '');

                    $('#nombre').addClass('resetear');
                    $('#telefono').addClass('resetear');
                    $('#direccion').addClass('resetear');
                    $('.mensaje-validacion').removeClass('mostrar');

                    $('#tituloModal').html('EDITAR PROVEEDOR');
                    $(".accion").attr("id", "editar");
                    $('#modalProveedor').modal('show');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    mostrarError("Error en el servidor.");
                }
            });
        }

        function eliminar(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar este proveedor?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "app/controllers/ProveedorController.php",
                        type: "POST",
                        dataType: "json",
                        data: { 
                            opcion: "eliminar",
                            id: id 
                        },
                        success: function (response) {
                            if (response.status === "success") {
                                $('#tablaProveedor').DataTable().ajax.reload(null, false);
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

        //funciones globales
        window.editar = editar;
        window.eliminar = eliminar;
        window.mostrarError = mostrarError;
        window.mostrarToast = mostrarToast;
    });
    </script>
</body>
</html>