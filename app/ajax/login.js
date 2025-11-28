const APP_URL = "https://farmacia2025-production.up.railway.app/";

$("#formLogin").on("submit", function (e) {
  e.preventDefault();
  
  var formData = new FormData($("#formLogin")[0]);
  formData.append("opcion", "ingresar");

  $.ajax({
    url: APP_URL + "app/controllers/AuthController.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        location.href = APP_URL + "inicio";
      } else {
        mostrarError(response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error completo:", {
        status: xhr.status,
        statusText: xhr.statusText,
        responseText: xhr.responseText,
        error: error
      });
      
      if (xhr.status === 404) {
        mostrarError("Servicio no encontrado. Verifique la URL.");
      } else if (xhr.status === 500) {
        mostrarError("Error interno del servidor.");
      } else {
        mostrarError("Error de conexión. Intente más tarde.");
      }
    }
  });
});



$("#showPassword").on("click", function () {
  if ($("#contrasenia").attr("type") === "password") {
    $("#contrasenia").attr("type", "text");
    $("#iconPassword").removeClass("ri-eye-fill");
    $("#iconPassword").addClass("ri-eye-off-fill");
  } else {
    $("#contrasenia").attr("type", "password");
    $("#iconPassword").removeClass("ri-eye-off-fill");
    $("#iconPassword").addClass("ri-eye-fill");
  }
});

function mostrarError(mensaje) {
  Swal.fire({
    title: "ERROR",
    text: mensaje,
    icon: "error",
    confirmButtonText: "Aceptar",
    allowOutsideClick: false,
  });
}
