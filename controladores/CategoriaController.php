<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}

require_once RECURSOS . '/conexion.php';
require_once MODELOS . '/categoria.php';

class categoriaController
{
    private mysqli $conn;

    function __construct() {}

    function obtenerCategorias(): array
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        $resultado = [];
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call obtenerCategorias(inicio(), fin())";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($id, $Nombre, $Descripcion);
                while ($stmt->fetch()) {
                    $resultado[count($resultado)] = new categoria((int) $id, (string) $Nombre, (string) $Descripcion);
                }

                $stmt->free_result();
                while ($stmt->next_result()) {
                    if ($stmt->store_result()) {
                        $stmt->free_result();
                    }
                }
                $this->conn->commit();
            } catch (mysqli_sql_exception $e) {
                throw new Exception($e->getMessage());
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            } finally {
                $this->conn->close();
            }
        } else {
            throw new Exception('Conexion Fallida.');
        }
        return $resultado;
    }

    function agregarCategoria(string $nombre, string $descripcion): void
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call insertarCategoria(?,?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('ss', $nombre, $descripcion);
                $stmt->execute();
                $this->conn->commit();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Conexion Fallida.');
        }
    }

    function actualizarCategoria(int $id, $nombre, $descripcion): void
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = 'call actualizarCategoria(?,' . ($nombre != null ? '?' : 'null') . ',' . ($descripcion != null ? '?' : 'null') . ')';
                $stmt = $this->conn->prepare($sql);
                if ($nombre != null && $descripcion != null) {
                    $stmt->bind_param('iss', $id, $nombre, $descripcion);
                } else if ($nombre != null) {
                    $stmt->bind_param('is', $id, $nombre);
                } else if ($descripcion != null) {
                    $stmt->bind_param('is', $id, $descripcion);
                } else {
                    $stmt->bind_param('i', $id);
                }
                $stmt->execute();
                $this->conn->commit();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Conexion Fallida.');
        }
    }

    function eliminarCategoria(int $id)
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call eliminarCategoria(?);";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $this->conn->commit();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            throw new Exception('Conexion Fallida.');
        }
    }

    function buscarCategoria(string $palabra): array
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        $resultado = [];
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call buscarCategoria(?, inicio(), fin())";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('s', $palabra);
                $stmt->execute();
                $stmt->bind_result($id, $Nombre, $Descripcion);
                while ($stmt->fetch()) {
                    $resultado[count($resultado)] = new categoria((int) $id, (string) $Nombre, (string) $Descripcion);
                }

                $stmt->free_result();
                while ($stmt->next_result()) {
                    if ($stmt->store_result()) {
                        $stmt->free_result();
                    }
                }
                $this->conn->commit();
            } catch (mysqli_sql_exception $e) {
                throw new Exception($e->getMessage());
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            } finally {
                $this->conn->close();
            }
        } else {
            throw new Exception('Conexion Fallida.');
        }
        return $resultado;
    }
}
