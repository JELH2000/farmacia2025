<?php
require_once 'server.php';

class Conexion{
    private static ?PDO $conexion = null;

    private function __construct() {}

    public static function conectar(): ?PDO
    {
        if (self::$conexion === null) {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT         => false,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$conexion = new PDO($dsn, DB_USER, DB_PASS, $opciones);
            } catch (PDOException $e) {
                error_log("Error de conexión: " . $e->getMessage());
                die("Error de conexión. Intente más tarde.");
            }
        }
        return self::$conexion;
    }
}