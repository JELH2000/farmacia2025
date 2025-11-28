<?php

declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class ProductoModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function getProductos(): array
    {
        try {
            $sql = "CALL obtenerProductos(-1, -1)";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getProductos: " . $e->getMessage());
            return [];
        }
    }

    public function getProductoById(int $id): ?array
    {
        try {
            $sql = "CALL buscarProductoPorId(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getProductoById: " . $e->getMessage());
            return null;
        }
    }

    public function existeNombre(string $nombre, int $idExcluir = 0): bool
    {
        try {

            $sql = "CALL buscarProducto(:palabra, -1, -1)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":palabra", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($productos as $producto) {
                if (strtolower($producto['Nombre']) === strtolower($nombre)) {
                    if ($idExcluir > 0 && $producto['idProducto'] != $idExcluir) {
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

    public function registrar(string $nombre, float $precio, string $descripcion, int $fkCategoria): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL insertarProducto(:nombre, :precio, :descripcion, NULL, :fkCategoria)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":precio", $precio, PDO::PARAM_STR);
            $stmt->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(":fkCategoria", $fkCategoria, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error registrar producto: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombre, float $precio, string $descripcion, int $fkCategoria): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL actualizarProducto(:id, :nombre, :precio, :descripcion, NULL, :fkCategoria)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":precio", $precio, PDO::PARAM_STR);
            $stmt->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(":fkCategoria", $fkCategoria, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error actualizar producto: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL eliminarProducto(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error eliminar producto: " . $e->getMessage());
            return false;
        }
    }

    public function estaEnUso(int $id): bool
    {
        try {
            // Verificar si el producto tiene importes asociados
            $sql = "SELECT COUNT(*) FROM importe WHERE fkProducto = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $importes = $stmt->fetchColumn();

            // Verificar si el producto tiene ventas asociadas
            $sql = "SELECT COUNT(*) FROM producto_muestra_detalleventa WHERE fkProducto = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $ventas = $stmt->fetchColumn();

            return ($importes > 0 || $ventas > 0);
        } catch (Throwable $e) {
            error_log("Error estaEnUso: " . $e->getMessage());
            return true;
        }
    }

    public function getCategorias(): array
    {
        try {
            $sql = "CALL obtenerCategorias(-1, -1)";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getCategorias: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalProductos(): int
    {
        try {
            $productos = $this->getProductos();
            return count($productos);
        } catch (Throwable $e) {
            error_log("Error getTotalProductos: " . $e->getMessage());
            return 0;
        }
    }
}
