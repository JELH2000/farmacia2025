<?php
header('Content-Type: application/json; charset=utf-8');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once __DIR__ . "/../models/EmpleadoModel.php";

class AuthController
{
    private $empleadoModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->initSession();
    }

    private function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function ingresar(): array
    {
        $usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : '';
        $contrasenia = isset($_POST["contrasenia"]) ? trim($_POST["contrasenia"]) : '';

        if (empty($usuario) || empty($contrasenia)) {
            return ["status" => "error", "message" => "Usuario y contraseña son obligatorios"];
        }

        $empleado = $this->empleadoModel->getEmpleadoLogin($usuario, $contrasenia);

        if ($empleado) {
            $_SESSION["empleado"] = [
                'idEmpleado' => $empleado['idEmpleado'],
                'Nombre' => $empleado['Nombre'],
                'Usuario' => $empleado['Usuario'],
                'Estado' => $empleado['Estado'],
                'login_time' => time()
            ];

            session_regenerate_id(true);

            return [
                "status" => "success", 
                "message" => "Inicio de sesión exitoso",
                "redirect" => "dashboard.php"
            ];
        } else {
            return ["status" => "error", "message" => "Usuario o contraseña incorrectos"];
        }
    }

    public function cerrar(): array
    {
        $_SESSION = array();
        session_destroy();
        return [
            "status" => "success", 
            "message" => "Sesión cerrada exitosamente",
            "redirect" => "index.php"
        ];
    }

    public function verificarSesion(): array
    {
        if (isset($_SESSION["empleado"])) {
            return ["status" => "success", "empleado" => $_SESSION["empleado"]];
        } else {
            return ["status" => "error", "message" => "No hay sesión activa"];
        }
    }
}

// Procesar solicitud
if ($_POST) {
    $authController = new AuthController();
    $opcion = isset($_POST["opcion"]) ? trim($_POST["opcion"]) : '';
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
        $response = ["status" => "error", "message" => "Error interno del servidor"];
    }

    echo json_encode($response);
    exit;
}