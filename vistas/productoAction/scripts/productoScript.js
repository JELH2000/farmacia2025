let memoriaCategorias = [];
let memoriaProductos = [];

$(document).ready(() => {
  $("#modalBtnGuardar").click((evt) => eventGuardar(evt));
  $("#modalBtnCancelar").click((evt) => {
    limpiarModal();
  });
  $("#modalBtnCerrar").click((evt) => {
    limpiarModal();
  });

  $("#btnBuscar").click((evt) => {
    buscarProducto();
  });
  cargarCategorias();
  cargarProductos({ option: "allProducto" });
  $("#modalBtnGuardar").data("estado", "Guardar");
});

async function cargarCategorias() {
  memoriaCategorias = [];
  let listaCategoria = $("#LPCategoria");
  await $.ajax({
    url: "../servers/serverProductos.php",
    type: "POST",
    data: { option: "listCategoria" },
    dataType: "json",
    success: (response) => {
      memoriaCategorias = response.datos;
    },
    error: (error) => {
      console.log(error);
    },
  });
  if (memoriaCategorias.length > 0) {
    listaCategoria.empty();
    let html = `<option value="0">Seleccionar Opcion</option>`;
    memoriaCategorias.forEach((dato) => {
      html += `<option value="${dato.id}">${dato.nombre}</option>`;
    });

    listaCategoria.append(html);
  }
}

async function cargarProductos(datos) {
  //{ option: "allProducto" }
  memoriaProductos = [];
  let bodyTable = $("#bodyTable");
  await $.ajax({
    url: "../servers/serverProductos.php",
    type: "POST",
    data: datos,
    dataType: "json",
    success: (response) => {
      memoriaProductos = response.datos;
    },
    error: (error) => {
      console.log(error);
    },
  });
  bodyTable.empty();
  if (memoriaProductos.length > 0) {
    let html = "";
    memoriaProductos.forEach((dato) => {
      let tmpCategoria = memoriaCategorias.find(
        (dat) => dat.id == dato.Categoria
      ).nombre;
      html += `<tr>
                <td>${dato.id}</td>
                <td>${dato.Nombre}</td>
                <td>${dato.Precio}</td>
                <td>${dato.Descripcion}</td>
                <td class="text-center">
                  <h3
                    class="bi bi-file-image-fill"
                    data-bs-toggle="modal"
                    data-bs-target="#modalImgProduct"
                    onclick="evtImagenClick(${dato.id})"
                  ></h3>
                </td>
                <td>${tmpCategoria}</td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="#"onclick="evtEditar(${dato.id})" class="btn btn-warning btn-sm btn-action"
                      >Editar</a
                    >
                    <form method="POST" action="#" style="display: inline">
                      <button
                        type="button"
                        class="btn btn-danger btn-sm btn-action"
                        onclick="evtEliminar(${dato.id})"
                      >
                        Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>`;
    });
    bodyTable.append(html);
  }
}

function evtImagenClick(id) {
  const data = new FormData();
  data.append("id", id);
  data.append("option", "fotoPoridProducto");
  fetch(`../servers/serverProductos.php`, {
    method: "POST",
    body: data,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al cargar la imagen.");
      }
      return response.blob();
    })
    .then((imageBlob) => {
      const imageObjectURL = URL.createObjectURL(imageBlob);
      $("#imgContent")[0].src = imageObjectURL;
    });
}

function evtEliminar(id) {
  let respuesta = confirm("¿Está seguro de eliminar este producto?");
  if (respuesta) {
    $.ajax({
      url: "../servers/serverProductos.php",
      type: "POST",
      data: { option: "eliminar", id: id },
      dataType: "json",
      success: (response) => {
        if (response.status == "exito") {
          Swal.fire({
            title: "Eliminado",
            text: response.message,
            icon: "success",
          });
          cargarProductos({ option: "allProducto" });
        } else {
          Swal.fire({
            title: "Error",
            text: response.message,
            icon: "error",
          });
        }
      },
      error: (error) => {
        Swal.fire({
          title: "Error",
          text: "Un error inesperado a ocurrido",
          icon: "error",
        });
      },
    });
  }
}

function evtEditar(id) {
  $("#formProducto").data("id", id);
  let seleccionado = memoriaProductos.find((datos) => datos.id == id);
  $("#formProducto").data("nombre", seleccionado.Nombre);
  $("#modalBtnGuardar").data("estado", "Editar");
  $("#modalBtnGuardar").empty();
  $("#modalBtnGuardar").append("Editar");
  $("#TIPNombre").val(seleccionado.Nombre);
  $("#NIPPrecio").val(seleccionado.Precio);
  $("#TAPDescripcion").val(seleccionado.Descripcion);
  $("#LPCategoria").val(seleccionado.Categoria);
  $("#crearEditarModal").modal("show");
}

function eventGuardar(evt) {
  if ($("#TIPNombre").val().trim().length == 0)
    $("#TIPNombre").addClass("is-invalid");
  else $("#TIPNombre").removeClass("is-invalid");

  if ($("#NIPPrecio").val().trim().length == 0)
    $("#NIPPrecio").addClass("is-invalid");
  else $("#NIPPrecio").removeClass("is-invalid");

  if ($("#TAPDescripcion").val().trim().length == 0)
    $("#TAPDescripcion").addClass("is-invalid");
  else $("#TAPDescripcion").removeClass("is-invalid");

  if ($("#modalBtnGuardar").data("estado") == "Guardar") {
    if ($("#IIPFoto")[0].files.length == 0)
      $("#IIPFoto").addClass("is-invalid");
    else $("#IIPFoto").removeClass("is-invalid");
  }

  if ($("#LPCategoria").val() == "0") $("#LPCategoria").addClass("is-invalid");
  else $("#LPCategoria").removeClass("is-invalid");

  if (
    $("#TIPNombre").val().trim().length != 0 &&
    $("#NIPPrecio").val().trim().length != 0 &&
    $("#TAPDescripcion").val().trim().length != 0 &&
    ($("#modalBtnGuardar").data("estado") == "Guardar"
      ? $("#IIPFoto")[0].files.length != 0
      : true) &&
    $("#LPCategoria").val() != "0"
  ) {
    $("#crearEditarModal").modal("hide");
    let formulario = new FormData(document.querySelector("#formProducto"));
    if ($("#modalBtnGuardar").data("estado") == "Guardar") {
      formulario.append("option", "crear");
    } else {
      formulario.append("option", "actualizar");
      if ($("#TIPNombre").val() == $("#formProducto").data("nombre"))
        formulario.append("nom", 0);
      else formulario.append("nom", 1);
      if ($("#IIPFoto")[0].files.length == 0) formulario.append("archivo", 0);
      else formulario.append("archivo", 1);
      formulario.append("id", $("#formProducto").data("id"));
    }

    $.ajax({
      url: "../servers/serverProductos.php",
      type: "POST",
      data: formulario,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.status == "exito") {
          Swal.fire({
            title: "Guardado",
            text: response.message,
            icon: "success",
          });
          cargarProductos({ option: "allProducto" });
        } else {
          Swal.fire({
            title: "Error",
            text: response.message,
            icon: "error",
          });
        }
      },
      error: (error) => {
        Swal.fire({
          title: "Error",
          text: "Un error inesperado a ocurrido",
          icon: "error",
        });
      },
    });
    limpiarModal();
  }
}

function limpiarModal() {
  $("#formProducto")[0].reset();
  $("#TIPNombre").removeClass("is-invalid");
  $("#NIPPrecio").removeClass("is-invalid");
  $("#TAPDescripcion").removeClass("is-invalid");
  $("#IIPFoto").removeClass("is-invalid");
  $("#LPCategoria").removeClass("is-invalid");
  $("#modalBtnGuardar").data("estado", "Guardar");
  $("#modalBtnGuardar").empty();
  $("#modalBtnGuardar").append("Guardar");
}

function buscarProducto() {
  let palabra = $("#buscar").val();
  if (palabra.trim().length != 0) {
    cargarProductos({ option: "buscar", buscar: palabra });
  } else {
    cargarProductos({ option: "allProducto" });
  }
}
