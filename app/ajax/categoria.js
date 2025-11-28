$(document).ready(function () {
    // Inicializar DataTable
    var table = $('#tablaCategoria').DataTable({
        ajax: {
            url: 'app/controllers/CategoriaController.php',
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
                title: 'Categorías',
                messageTop: 'Listado de categorías de productos',
                text: 'PDF',
                className: 'btn-danger',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Categorías',
                messageTop: 'Listado de categorías de productos',
                text: 'Excel',
                className: 'btn-success',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            },
            {
                extend: 'print',
                title: 'Categorías',
                messageTop: 'Listado de categorías de productos',
                text: 'Imprimir',
                className: 'btn-info',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }
        ]
    });

    table.buttons().container().appendTo('.buttons-extras');
    $('.buttons-extras').hide();
});

$("#nuevo").on("click", function(){
    $('#idCategoria').val("");
    $('#nombre').val("");
    $('#descripcion').val("");

    $('#nombre').addClass('resetear');
    $('#descripcion').addClass('resetear');
    $('.mensaje-validacion').removeClass('mostrar');

    $('#tituloModal').html('NUEVA CATEGORÍA');
    $(".accion").attr("id","guardar");
    $('#modalCategoria').modal('show');
});

$("#formCategoria").on("submit", function (e) {
    e.preventDefault();
    let accion = $(".accion").attr("id");

    let nombreVal = limpiarInput($("#nombre").val());
    let descripcionVal = limpiarInput($("#descripcion").val());

    if(!isValidNombre(nombreVal)){
        mostrarError("Por favor, ingrese un nombre válido (2-100 caracteres).");
        return;
    }

    if(!isValidDescripcion(descripcionVal)){
        mostrarError("La descripción no debe exceder los 500 caracteres.");
        return;
    }

    guardaryeditar(e, accion);
});

var nombre = $("#nombre");
var descripcion = $("#descripcion");

nombre.on("input", function() {
    this.value = limpiarInput(this.value);
    crearListener(isValidNombre)(event);
}).on("keypress", function(e) {
    if (!/^[a-zA-Z\u00C0-\u017F\s]$/.test(e.key)) {
        e.preventDefault();
    }
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
    return /^[a-zA-Z\u00C0-\u017F\s]{2,100}$/.test(nombre);
}

function isValidDescripcion(descripcion) {
    return descripcion.length <= 500;
}

function limpiarInput(text) {
    return text.replace(/^\s+/, '').replace(/\s\s+/g, ' ');
}

function guardaryeditar(e, accion) {
    e.preventDefault();
    let formData = new FormData($("#formCategoria")[0]);
    formData.append("opcion", (accion === "guardar") ? "agregar" : "editar");

    $.ajax({
        url: "app/controllers/CategoriaController.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            $("#modalCategoria").modal('hide');
            if (response.status === "success") {
                $('#formCategoria')[0].reset();
                $('#tablaCategoria').DataTable().ajax.reload(null, false);
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
        url: "app/controllers/CategoriaController.php",
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

            const categoria = data.data;
            $('#idCategoria').val(categoria.idCategoria);
            $('#nombre').val(categoria.Nombre);
            $('#descripcion').val(categoria.Descripcion || '');

            $('#nombre').addClass('resetear');
            $('#descripcion').addClass('resetear');
            $('.mensaje-validacion').removeClass('mostrar');

            $('#tituloModal').html('EDITAR CATEGORÍA');
            $(".accion").attr("id", "editar");
            $('#modalCategoria').modal('show');
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            mostrarError("Error en el servidor.");
        }
    });
}

function eliminar(id) {
    Swal.fire({
        title: '¿Está seguro de eliminar esta categoría?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "app/controllers/CategoriaController.php",
                type: "POST",
                dataType: "json",
                data: { 
                    opcion: "eliminar",
                    id: id 
                },
                success: function (response) {
                    if (response.status === "success") {
                        $('#tablaCategoria').DataTable().ajax.reload(null, false);
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