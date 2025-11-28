<?php
header('Content-Type: application/json');

// Incluir la conexión a la base de datos
require_once '../config/database.php';

try {
    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    $opcion = $_POST['opcion'] ?? '';
    
    if ($opcion === 'ingresar') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validaciones básicas
        if (empty($email) || empty($password)) {
            throw new Exception('Email y contraseña son requeridos');
        }
        
        $database = new Database();
        $db = $database->getConnection();
        
        if ($db === null) {
            throw new Exception('Error de conexión a la base de datos');
        }
        
        // Consulta para verificar usuario
        $query = "SELECT id, nombre, email, password FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña (asumiendo que está hasheada)
            if (password_verify($password, $row['password'])) {
                // Iniciar sesión
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nombre'];
                $_SESSION['user_email'] = $row['email'];
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login exitoso'
                ]);
            } else {
                throw new Exception('Credenciales incorrectas');
            }
        } else {
            throw new Exception('Usuario no encontrado');
        }
        
    } else {
        throw new Exception('Opción no válida');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>