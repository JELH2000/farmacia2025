$(document).ready(function () {
    // Inicializar DataTable
    var table = $('#tablaEmpleado').DataTable({
        ajax: {
            url: 'app/controllers/EmpleadoController.php',
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
    $('#btnPdf').on('click', function() {
        table.button('.buttons-pdf').trigger();
    });

    $('#btnExcel').on('click', function() {
        table.button('.buttons-excel').trigger();
    });

    $('#btnPrint').on('click', function() {
        table.button('.buttons-print').trigger();
    });

    // Agregar botones de exportación al DataTable (ocultos)
    new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'pdfHtml5',
                title: 'Empleados',
                messageTop: 'Listado de empleados del sistema',
                text: 'PDF',
                className: 'btn-danger',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Empleados',
                messageTop: 'Listado de empleados del sistema',
                text: 'Excel',
                className: 'btn-success',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'print',
                title: 'Empleados',
                messageTop: 'Listado de empleados del sistema',
                text: 'Imprimir',
                className: 'btn-info',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ]
    });

    table.buttons().container().appendTo('.buttons-extras');
    $('.buttons-extras').hide();
});

$("#nuevo").on("click", function(){
    $('#idEmpleado').val("");
    $('#nombre').val("");
    $('#usuario').val("");
    $('#contrasenia').val("");
    $('#estado').val("Activo");

    $('#nombre').addClass('resetear');
    $('#usuario').addClass('resetear');
    $('#contrasenia').addClass('resetear');
    $('.mensaje-validacion').removeClass('mostrar');

    $('#tituloModal').html('NUEVO EMPLEADO');
    $(".accion").attr("id","guardar");
    $('#modalEmpleado').modal('show');
});

$("#formEmpleado").on("submit", function (e) {
    e.preventDefault();
    let accion = $(".accion").attr("id");

    let nombreVal = limpiarInput($("#nombre").val());
    let usuarioVal = limpiarInput($("#usuario").val());
    let contraseniaVal = $("#contrasenia").val();

    if(!isValidNombre(nombreVal)){
        mostrarError("Por favor, ingrese un nombre válido (2-100 caracteres).");
        return;
    }

    

    if(!isValidContrasenia(contraseniaVal)){
        mostrarError("La contraseña debe tener entre 8 y 16 caracteres.");
        return;
    }

    guardaryeditar(e, accion);
});

var nombre = $("#nombre");
var usuario = $("#usuario");
var contrasenia = $("#contrasenia");

nombre.on("input", function() {
    this.value = limpiarInput(this.value);
    crearListener(isValidNombre)(event);
});

usuario.on("input", function() {
    this.value = limpiarInput(this.value);
    crearListenerUsuario(isValidUsuario)(event);
});

contrasenia.on("input", function() {
    crearListenerContrasenia(isValidContrasenia)(event);
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

function crearListenerUsuario(validator) {
    return e => {
        const input = e.target;
        const text = e.target.value;
        const valid = validator(text);
        const showTip = text !== "" && !valid;
        const toolTip = e.target.nextElementSibling;
        showOrHideTio(showTip, toolTip, input);
    };
}

function crearListenerContrasenia(validator) {
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
    return /^[a-zA-Z\u00C0-\u017F\s]{2,50}$/.test(nombre);
}



function isValidContrasenia(contrasenia) {
    return contrasenia.length >= 5 && contrasenia.length <= 10;
}

function limpiarInput(text) {
    return text.replace(/^\s+/, '').replace(/\s\s+/g, ' ');
}

function guardaryeditar(e, accion) {
    e.preventDefault();
    let formData = new FormData($("#formEmpleado")[0]);
    formData.append("opcion", (accion === "guardar") ? "agregar" : "editar");

    $.ajax({
        url: "app/controllers/EmpleadoController.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $("#modalEmpleado").modal('hide');
            if (response.status === "success") {
                $('#formEmpleado')[0].reset();
                $('#tablaEmpleado').DataTable().ajax.reload(null, false);
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
        url: "app/controllers/EmpleadoController.php",
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

            const empleado = data.data;
            $('#idEmpleado').val(empleado.idEmpleado);
            $('#nombre').val(empleado.Nombre);
            $('#usuario').val(empleado.Usuario);
            $('#contrasenia').val(empleado.contrasenia);
            $('#estado').val(empleado.Estado);

            $('#nombre').addClass('resetear');
            $('#usuario').addClass('resetear');
            $('#contrasenia').addClass('resetear');
            $('.mensaje-validacion').removeClass('mostrar');

            $('#tituloModal').html('EDITAR EMPLEADO');
            $(".accion").attr("id", "editar");
            $('#modalEmpleado').modal('show');
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            mostrarError("Error en el servidor.");
        }
    });
}

function eliminar(id) {
    Swal.fire({
        title: '¿Está seguro de eliminar este empleado?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "app/controllers/EmpleadoController.php",
                type: "POST",
                dataType: "json",
                data: { 
                    opcion: "eliminar",
                    id: id 
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('#tablaEmpleado').DataTable().ajax.reload(null, false);
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