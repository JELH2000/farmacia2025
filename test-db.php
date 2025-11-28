<?php
require_once 'config/database.php';

// If the Database class is not defined in 'config/database.php', define it here
if (!class_exists('Database')) {
    class Database {
        private $conn;

        public function getConnection() {
            $this->conn = new PDO("mysql:host=localhost;dbname=testdb", "root", "");
            return $this->conn;
        }
    }
}

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Conexión exitosa a la BD";
} else {
    echo "Error conectando a la BD";
}
?>