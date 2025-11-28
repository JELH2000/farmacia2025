<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class ImporteModel {
    private PDO $conexion;

    public function __construct(){
        $this->conexion = Conexion::conectar();
    }

    public function getImportes(): array{
        try {
            $sql = "SELECT i.*, p.Nombre as ProductoNombre, pr.Nombre as ProveedorNombre 
                    FROM importe i 
                    LEFT JOIN producto p ON i.fkProducto = p.idProducto 
                    LEFT JOIN proveedor pr ON i.fkProveedor = pr.idProveedor 
                    ORDER BY i.idImporte DESC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getImportes: " . $e->getMessage());
            return [];
        }
    }

    public function getImporteById(int $id): ?array{
        try {
            $sql = "SELECT * FROM importe WHERE idImporte = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getImporteById: " . $e->getMessage());
            return null;
        }
    }

    public function existeCodigo(int $codigo, int $idExcluir = 0): bool{
        try {
            $sql = "SELECT COUNT(*) FROM importe WHERE CodigoProducto = :codigo";
            $params = [":codigo" => $codigo];
            
            if ($idExcluir > 0) {
                $sql .= " AND idImporte != :id";
                $params[":id"] = $idExcluir;
            }
            
            $stmt = $this->conexion->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Throwable $e) {
            error_log("Error existeCodigo: " . $e->getMessage());
            return false;
        }
    }

    public function registrar(string $vencimiento, string $fechaCompra, int $codigoProducto, string $estado, int $fkProducto, int $fkProveedor): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL insertarImporte(:vencimiento, :fechaCompra, :codigoProducto, :estado, :fkProducto, :fkProveedor)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":vencimiento", $vencimiento, PDO::PARAM_STR);
            $stmt->bindValue(":fechaCompra", $fechaCompra, PDO::PARAM_STR);
            $stmt->bindValue(":codigoProducto", $codigoProducto, PDO::PARAM_INT);
            $stmt->bindValue(":estado", $estado, PDO::PARAM_STR);
            $stmt->bindValue(":fkProducto", $fkProducto, PDO::PARAM_INT);
            $stmt->bindValue(":fkProveedor", $fkProveedor, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error registrar importe: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $vencimiento, string $fechaCompra, int $codigoProducto, string $estado, int $fkProducto, int $fkProveedor): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL actualizarImporte(:id, :vencimiento, :fechaCompra, :codigoProducto, :estado, :fkProducto, :fkProveedor)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":vencimiento", $vencimiento, PDO::PARAM_STR);
            $stmt->bindValue(":fechaCompra", $fechaCompra, PDO::PARAM_STR);
            $stmt->bindValue(":codigoProducto", $codigoProducto, PDO::PARAM_INT);
            $stmt->bindValue(":estado", $estado, PDO::PARAM_STR);
            $stmt->bindValue(":fkProducto", $fkProducto, PDO::PARAM_INT);
            $stmt->bindValue(":fkProveedor", $fkProveedor, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error actualizar importe: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL eliminarImporte(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error eliminar importe: " . $e->getMessage());
            return false;
        }
    }

    public function getProductos(): array{
        try {
            $sql = "SELECT idProducto, Nombre FROM producto WHERE idProducto IN (SELECT DISTINCT fkProducto FROM importe) ORDER BY Nombre";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getProductos: " . $e->getMessage());
            return [];
        }
    }

    public function getProveedores(): array{
        try {
            $sql = "SELECT idProveedor, Nombre FROM proveedor WHERE idProveedor IN (SELECT DISTINCT fkProveedor FROM importe) ORDER BY Nombre";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getProveedores: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalImportes(): int {
        try {
            $sql = "SELECT COUNT(*) FROM importe";
            $stmt = $this->conexion->query($sql);
            return (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            error_log("Error getTotalImportes: " . $e->getMessage());
            return 0;
        }
    }

    public function getImportesPorProducto(int $productoId): array{
        try {
            $sql = "CALL importePorProducto(:productoId, NULL, NULL, 'Disponible', -1, -1)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":productoId", $productoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getImportesPorProducto: " . $e->getMessage());
            return [];
        }
    }

    public function getImportesPorProveedor(int $proveedorId): array{
        try {
            $sql = "CALL importePorProveedor(:proveedorId, NULL, NULL, -1, -1)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":proveedorId", $proveedorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getImportesPorProveedor: " . $e->getMessage());
            return [];
        }
    }
}
?>