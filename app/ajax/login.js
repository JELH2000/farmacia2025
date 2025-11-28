$("#formLogin").on("submit", function (e) {
  e.preventDefault();
  var formData = new FormData($("#formLogin")[0]);
  formData.append("opcion", "ingresar");

  $.ajax({
    url: "app/controllers/AuthController.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        location.href = "inicio";
      } else {
        mostrarError(response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("=== ERROR AJAX ===");
      console.error("Status:", status);
      console.error("Error:", error);
      console.error("HTTP Status:", xhr.status);
      console.error("URL:", "app/controllers/AuthController.php");
      console.error("Respuesta completa:", xhr.responseText);
      
      // Verifica si es un error 404 (archivo no encontrado)
      if (xhr.status === 404) {
        mostrarError("El servicio de autenticación no está disponible (404)");
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
