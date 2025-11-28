<?php

declare(strict_types=1);

require_once __DIR__ . "/../../config/conexion.php";

class EmpleadoModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function getEmpleados(): array
    {
        try {
            
            $sql = "CALL obtenerEmpleados('Activo', -1, -1)";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getEmpleados: " . $e->getMessage());
            return [];
        }
    }

    public function getAllEmpleados(): array
    {
        try {
            
            $sql = "SELECT * FROM empleado ORDER BY idEmpleado";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error getAllEmpleados: " . $e->getMessage());
            return [];
        }
    }
    public function getEmpleadoById(int $id): ?array
    {
        try {
            $sql = "CALL buscarEmpleadoPorId(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getEmpleadoById: " . $e->getMessage());
            return null;
        }
    }

    public function getEmpleadoByUsuario(string $usuario): ?array
    {
        try {
            $sql = "CALL buscarEmpleadoPorUsuario(:usuario)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log("Error getEmpleadoByUsuario: " . $e->getMessage());
            return null;
        }
    }

    public function existeUsuario(string $usuario, int $idExcluir = 0): bool
    {
        try {
            $empleado = $this->getEmpleadoByUsuario($usuario);
            if ($empleado) {
                if ($idExcluir > 0 && $empleado['idEmpleado'] != $idExcluir) {
                    return true;
                } elseif ($idExcluir === 0) {
                    return true;
                }
            }
            return false;
        } catch (Throwable $e) {
            error_log("Error existeUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function registrar(string $nombre, string $usuario, string $contrasenia, string $estado): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL insertarEmpleado(:nombre, :usuario, :contrasenia, NULL, :estado)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->bindValue(":contrasenia", $contrasenia, PDO::PARAM_STR);
            $stmt->bindValue(":estado", $estado, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error registrar empleado: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombre, string $usuario, string $contrasenia, string $estado): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL actualizarEmpleado(:id, :nombre, :usuario, :contrasenia, NULL, :estado)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->bindValue(":contrasenia", $contrasenia, PDO::PARAM_STR);
            $stmt->bindValue(":estado", $estado, PDO::PARAM_STR);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error actualizar empleado: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "CALL eliminarEmpleado(:id)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            error_log("Error eliminar empleado: " . $e->getMessage());
            return false;
        }
    }

    public function estaEnUso(int $id): bool
    {
        try {
            
            $sql = "SELECT COUNT(*) FROM detalleventa WHERE fkEmpleado = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Throwable $e) {
            error_log("Error estaEnUso: " . $e->getMessage());
            return true;
        }
    }

    public function getTotalEmpleados(): int
    {
        try {
            $empleados = $this->getEmpleados();
            return count($empleados);
        } catch (Throwable $e) {
            error_log("Error getTotalEmpleados: " . $e->getMessage());
            return 0;
        }
    }

    
    public function getEmpleadoLogin(string $usuario, string $contrasenia): ?array
    {
        try {
            $empleado = $this->getEmpleadoByUsuario($usuario);
            if ($empleado && $empleado['contrasenia'] === $contrasenia && $empleado['Estado'] === 'Activo') {
               
                unset($empleado['contrasenia']);
                return $empleado;
            }
            return null;
        } catch (Throwable $e) {
            error_log("Error getEmpleadoLogin: " . $e->getMessage());
            return null;
        }
    }
}
