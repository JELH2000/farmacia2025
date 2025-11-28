<?php
// test-auth.php
header('Content-Type: application/json');

// Simular datos POST
$_POST = [
    'opcion' => 'ingresar',
    'email' => 'test@example.com', // Cambia por un email que exista
    'password' => 'password123'    // Cambia por la contraseña correcta
];

// Incluir y probar el controlador
require_once 'app/controllers/AuthController.php';
?>