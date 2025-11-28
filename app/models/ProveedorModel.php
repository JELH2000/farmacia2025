<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class ProveedorModel {
    private PDO $conexion;

    public function __construct(){
        $this->conexion = Conexion::conectar();
    }

    public function getProveedores(): array{
        try {
            $sql = "CALL obtenerProveedores(-1, -1)";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getProveedores: " . $e->getMessage());
            return [];
        }
    }

    public function getProveedorById(int $id): ?array{
        try {
            $sql = "CALL proveedorPorId(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getProveedorById: " . $e->getMessage());
            return null;
        }
    }

    public function existeNombre(string $nombre, int $idExcluir = 0): bool{
        try {
            // Buscar por nombre usando el procedimiento de búsqueda
            $sql = "CALL buscarProveedor(:palabra, -1, -1)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":palabra", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($proveedores as $proveedor) {
                if (strtolower($proveedor['Nombre']) === strtolower($nombre)) {
                    if ($idExcluir > 0 && $proveedor['idProveedor'] != $idExcluir) {
                        return true;
                    } elseif ($idExcluir === 0) {
                        return true;
                    }
                }
            }
            return false;
        } catch (Throwable $e) {
            error_log("Error existeNombre: " . $e->getMessage());
            return false;
        }
    }

    public function registrar(string $nombre, string $telefono, string $direccion): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL insertarProveedor(:nombre, :telefono, :direccion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindValue(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error registrar proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombre, string $telefono, string $direccion): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL actualizarProveedor(:id, :nombre, :telefono, :direccion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindValue(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error actualizar proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL eliminarProveedor(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error eliminar proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function estaEnUso(int $id): bool{
        try {
            // Verificar si el proveedor tiene importes asociados
            $sql = "SELECT COUNT(*) FROM importe WHERE fkProveedor = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Throwable $e) {
            error_log("Error estaEnUso: " . $e->getMessage());
            return true;
        }
    }

    public function getTotalProveedores(): int {
        try {
            $proveedores = $this->getProveedores();
            return count($proveedores);
        } catch (Throwable $e) {
            error_log("Error getTotalProveedores: " . $e->getMessage());
            return 0;
        }
    }
}
?>