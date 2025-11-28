$(document).ready(function () {
    // Mostrar/ocultar contraseña
    $('#showPassword').on('click', function() {
        const passwordInput = $('#contrasenia');
        const icon = $('#iconPassword');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('ri-eye-fill').addClass('ri-eye-off-fill');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('ri-eye-off-fill').addClass('ri-eye-fill');
        }
    });


    // Envío del formulario
    $('#formLogin').on('submit', function(e) {
        e.preventDefault();
        
        const usuario = $('#usuario').val().trim();
        const contrasenia = $('#contrasenia').val();


        // Mostrar loading
        const btnSubmit = $(this).find('button[type="submit"]');
        const originalText = btnSubmit.html();
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Entrando...');

        // Login
        $.ajax({
            url: 'app/controllers/AuthController.php',
            type: 'POST',
            data: {
                opcion: 'ingresar',
                usuario: usuario,
                contrasenia: contrasenia
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Para debug
                
                if (response.status === 'success') {
                    mostrarExito(response.message);
                    setTimeout(() => {
                        // Redirigir a la página especificada o por defecto
                        window.location.href = response.redirect || 'dashboard.php';
                    }, 1500);
                } else {
                    mostrarError(response.message);
                    btnSubmit.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en login:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                mostrarError('Error en el servidor. Intente más tarde.');
                btnSubmit.prop('disabled', false).html(originalText);
            }
        });
    });

    // Enter key submit
    $('#usuario, #contrasenia').on('keypress', function(e) {
        if (e.which === 13) {
            $('#formLogin').submit();
        }
    });
});

// Funciones de validación
function isValidUsuario(usuario) {
    return /^[a-zA-Z0-9_]{3,20}$/.test(usuario);
}

function isValidContrasenia(contrasenia) {
    return contrasenia.length >= 5 && contrasenia.length <= 10;
}

function validarCampo(campo, validator) {
    const valor = campo.val().trim();
    const esValido = validator(valor);
    
    if (valor === '') {
        campo.removeClass('is-valid is-invalid');
    } else if (esValido) {
        campo.removeClass('is-invalid').addClass('is-valid');
    } else {
        campo.removeClass('is-valid').addClass('is-invalid');
    }
}

function mostrarError(mensaje) {
    Swal.fire({
        title: 'Error',
        text: mensaje,
        icon: 'error',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#dc3545'
    });
}

function mostrarExito(mensaje) {
    Swal.fire({
        title: 'Éxito',
        text: mensaje,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}