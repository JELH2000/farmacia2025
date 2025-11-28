let memoriCategoria = [];

$(document).ready(() => {
  $("#modalBtnGuardar").click((evt) => eventGuardar(evt));
  $("#modalBtnCancelar").click((evt) => {
    limpiarModal();
  });
  $("#modalBtnCerrar").click((evt) => {
    limpiarModal();
  });
  $("#btnBuscar").click((evt) => {
    if ($("#buscar").val().trim().length != 0) {
      cargarCategorias({
        option: "buscarCategoria",
        palabra: $("#buscar").val(),
      });
    } else {
      cargarCategorias({ option: "allCategori" });
    }
  });
  cargarCategorias({ option: "allCategori" });
  $("#modalBtnGuardar").data("estado", "Guardar");
});

function eventGuardar(evt) {
  if ($("#ITNombre").val().trim().length == 0)
    $("#ITNombre").addClass("is-invalid");
  else $("#ITNombre").removeClass("is-invalid");
  let datos = {
    option:
      $("#modalBtnGuardar").data("estado") == "Guardar"
        ? "crear"
        : "actualizar",
    nombre: $("#ITNombre").val(),
    descripcion: $("#TADescripcion").val(),
  };

  if ($("#modalBtnGuardar").data("estado") != "Guardar")
    datos["id"] = $("#formCategoria").data("id");
  if ($("#ITNombre").val() == $("#formCategoria").data("nombre"))
    datos["nombreStatus"] = 0;
  else datos["nombreStatus"] = 1;

  if ($("#ITNombre").val().trim().length != 0) {
    $.ajax({
      url: "../servers/serverCategorias.php",
      type: "POST",
      data: datos,
      dataType: "json",
      success: (response) => {
        if (response.status == "exito") {
          Swal.fire({
            title: "Guardado",
            text: response.message,
            icon: "success",
          });
          cargarCategorias({ option: "allCategori" });
        } else {
          Swal.fire({
            title: "Error",
            text: response.message,
            icon: "error",
          });
        }
      },
      error: (error) => {
        console.log(error);
        Swal.fire({
          title: "Error",
          text: "Un error inesperado a ocurrido",
          icon: "error",
        });
      },
    });
    $("#crearEditarModal").modal("hide");
    limpiarModal();
  }
}

async function cargarCategorias(datos) {
  $.ajax({
    url: "../servers/serverCategorias.php",
    type: "POST",
    data: datos,
    dataType: "json",
    success: (response) => {
      memoriCategoria = response.datos;
      let bodyTable = $("#bodyTable");
      let html = ``;
      memoriCategoria.forEach((dato) => {
        html += `<tr>
                <td>${dato.id}</td>
                <td>${dato.nombre}</td>
                <td>${dato.descripcion}</td>
                <td>
                  <div class="btn-group" role="group">
                    <a onclick="evetEditar(${dato.id})" class="btn btn-warning btn-sm btn-action"
                      >Editar</a
                    >
                      <button
                        class="btn btn-danger btn-sm btn-action"
                        onclick="evetEliminar(${dato.id})"
                      >
                        Eliminar
                      </button>
                  </div>
                </td>
              </tr>`;
      });
      bodyTable.empty();
      bodyTable.append(html);
    },
    error: (error) => {
      console.log(error);
    },
  });
}

function evetEliminar(id) {
  $eleccion = confirm("¿Está seguro de eliminar esta categoria?");
  if ($eleccion) {
    $.ajax({
      url: "../servers/serverCategorias.php",
      type: "POST",
      data: { option: "eliminar", id: id },
      dataType: "json",
      success: (response) => {
        if (response.status == "exito") {
          Swal.fire({
            title: "Guardado",
            text: response.message,
            icon: "success",
          });
          cargarCategorias({ option: "allCategori" });
        } else {
          Swal.fire({
            title: "Error",
            text: response.message,
            icon: "error",
          });
        }
      },
      error: (error) => {
        console.log(error);
        Swal.fire({
          title: "Error",
          text: "Un error inesperado a ocurrido",
          icon: "error",
        });
      },
    });
  }
}

function evetEditar(id) {
  let eleccion = memoriCategoria.find((data) => data.id == id);
  $("#formCategoria").data("id", id);
  $("#formCategoria").data("nombre", eleccion.nombre);
  $("#modalBtnGuardar").empty();
  $("#modalBtnGuardar").data("estado", "Editar");
  $("#modalBtnGuardar")[0].innerHTML = "Editar";
  $("#ITNombre").val(eleccion.nombre);
  $("#TADescripcion").val(eleccion.descripcion);
  $("#crearEditarModal").modal("show");
}

function limpiarModal() {
  $("#ITNombre").removeClass("is-invalid");
  $("#modalBtnGuardar").empty();
  $("#modalBtnGuardar").data("estado", "Guardar");
  $("#modalBtnGuardar")[0].innerHTML = "Guardar";
  $("#formCategoria")[0].reset();
}
