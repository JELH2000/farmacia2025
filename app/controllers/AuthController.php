<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/EmpleadoModel.php";
// require_once __DIR__ . "/../models/EncriptarModel.php"; // Si usas encriptación

class AuthController
{
    private EmpleadoModel $empleadoModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
    }

    public function ingresar(): array
    {
        $usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : null;
        $contrasenia = isset($_POST["contrasenia"]) ? trim($_POST["contrasenia"]) : null;

        if (empty($usuario) || empty($contrasenia)) {
            return ["status" => "error", "message" => "Usuario y contraseña son obligatorios"];
        }

        $empleado = $this->empleadoModel->getEmpleadoLogin($usuario, $contrasenia);

        if ($empleado) {
            session_start();
            // Guardar en ambas variables de sesión para compatibilidad
            $_SESSION["empleado"] = $empleado;
            $_SESSION["usuario"] = $empleado; // Compatibilidad con código existente

            // DEBUG: Verificar qué se está guardando
            error_log("Empleado logueado: " . print_r($empleado, true));

            return ["status" => "success", "empleado" => $empleado];
        } else {
            return ["status" => "error", "message" => "Usuario o contraseña incorrectos"];
        }
    }

    public function cerrar(): array
    {
        session_start();
        session_destroy();
        return ["status" => "success", "message" => "Sesión cerrada exitosamente"];
    }

    public function verificarSesion(): array
    {
        session_start();
        if (isset($_SESSION["empleado"])) {
            return ["status" => "success", "empleado" => $_SESSION["empleado"]];
        } else {
            return ["status" => "error", "message" => "No hay sesión activa"];
        }
    }
}

// Procesar la solicitud
$authController = new AuthController();
$opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : null;
$response = ["status" => "error", "message" => "Opción inválida"];

try {
    switch ($opcion) {
        case "ingresar":
            $response = $authController->ingresar();
            break;

        case "cerrar":
            $response = $authController->cerrar();
            break;

        case "verificar":
            $response = $authController->verificarSesion();
            break;

        default:
            $response = ["status" => "error", "message" => "Opción no válida"];
            break;
    }
} catch (Throwable $e) {
    error_log("Error en AuthController: " . $e->getMessage());
    $response = ["status" => "error", "message" => "Ocurrió un error en el sistema"];
}

echo json_encode($response);
