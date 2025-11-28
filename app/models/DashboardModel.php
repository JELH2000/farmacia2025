<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class DashboardModel {
    private PDO $conexion;

    public function __construct(){
        $this->conexion = Conexion::conectar();
    }

    public function getEstadisticasGenerales(): array{
        try {
            $estadisticas = [];

            
            $sql = "SELECT COUNT(*) as total FROM producto";
            $stmt = $this->conexion->query($sql);
            $estadisticas['totalProductos'] = (int)$stmt->fetchColumn();

            
            $sql = "SELECT COALESCE(SUM(Total), 0) as total FROM detalleventa WHERE DATE(Fecha) = CURDATE()";
            $stmt = $this->conexion->query($sql);
            $estadisticas['ventasHoy'] = (float)$stmt->fetchColumn();

            
            $sql = "SELECT COUNT(DISTINCT fkProducto) as disponibles FROM importe WHERE Estado = 'Disponible'";
            $stmt = $this->conexion->query($sql);
            $estadisticas['productosDisponibles'] = (int)$stmt->fetchColumn();

            
            $sql = "SELECT COUNT(DISTINCT fkProducto) as agotados FROM importe WHERE Estado = 'Agotado'";
            $stmt = $this->conexion->query($sql);
            $estadisticas['productosAgotados'] = (int)$stmt->fetchColumn();

            return $estadisticas;

        } catch (Throwable $e) {
            error_log("Error getEstadisticasGenerales: " . $e->getMessage());
            return [
                'totalProductos' => 0,
                'ventasHoy' => 0,
                'productosDisponibles' => 0,
                'productosAgotados' => 0
            ];
        }
    }
}
?>