<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class CategoriaModel {
    private PDO $conexion;
 //sean usa sado los metodos call asi como los que useo en le video que mando y siguendo la misma logica de programacion del documento de apollo
    public function __construct(){
        $this->conexion = Conexion::conectar();
    }

    public function getCategorias(): array{
        try {
            $sql = "CALL obtenerCategorias(-1, -1)";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getCategorias: " . $e->getMessage());
            return [];
        }
    }

    public function getCategoriaById(int $id): ?array{
        try {
            $sql = "CALL buscarCategoriaPorId(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getCategoriaById: " . $e->getMessage());
            return null;
        }
    }

    public function existeNombre(string $nombre, int $idExcluir = 0): bool{
        try {
            // buscar por nombre usando el procedimiento 
            $sql = "CALL buscarCategoria(:palabra, -1, -1)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":palabra", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($categorias as $categoria) {
                if (strtolower($categoria['Nombre']) === strtolower($nombre)) {
                    if ($idExcluir > 0 && $categoria['idCategoria'] != $idExcluir) {
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

    public function registrar(string $nombre, string $descripcion): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL insertarCategoria(:nombre, :descripcion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error registrar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombre, string $descripcion): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL actualizarCategoria(:id, :nombre, :descripcion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error actualizar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool{
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL eliminarCategoria(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error eliminar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function estaEnUso(int $id): bool{
        try {
            // Verificar si hay productos asociados a esta categoría
            $sql = "SELECT COUNT(*) FROM producto WHERE fkCategoria = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Throwable $e) {
            error_log("Error estaEnUso: " . $e->getMessage());
            return true; //asumir que está en uso si hay error
        }
    }

    public function getTotalCategorias(): int {
        try {
            $categorias = $this->getCategorias();
            return count($categorias);
        } catch (Throwable $e) {
            error_log("Error getTotalCategorias: " . $e->getMessage());
            return 0;
        }
    }
}
?>