<?php
if (!defined('CONFIG')) require_once dirname(__DIR__) . '/config.php';

require_once MODELOS . '/producto.php';

class productoController
{
    private mysqli $conn;

    function __construct() {}

    function obtenerProductos(): array
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        $resultado = [];
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call obtenerProductos(inicio(), fin())";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($id, $Nombre, $Precio, $Descripcion, $Foto, $idCategoria);
                while ($stmt->fetch()) {
                    $resultado[count($resultado)] = new producto((int) $id, (string) $Nombre, (float) $Precio, (string) $Descripcion, (string) $Foto, new Categoria((int) $idCategoria, "", ""));
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

    function actualizarProducto(int $id, $nombre, $precio, $descripcion, $foto, $idCategoria)
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call actualizarProducto(
                " . $id . ",
                " . ($nombre != null ? "'" . $nombre . "'"  : "null") . ",
                " . ($precio != null ? $precio  : "null") . ",
                " . ($descripcion != null ? "'" . $descripcion . "'"  : "null") . ",
                " . ($foto != null ? "?"  : "null") . ",
                " . ($idCategoria != null ? $idCategoria  : "null") . "
                )";
                $stmt = $this->conn->prepare($sql);
                if ($foto != null) {
                    $stmt->bind_param('s', $foto);
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

    function agregarProducto(string $nombre, float $precio, $descripcion, $foto, int $idCategoria): void
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call insertarProducto(?, ?, " . ($descripcion != null ? '?' : 'null') . ", " . ($foto != null ? '?' : 'null') . ", ?)";
                $stmt = $this->conn->prepare($sql);
                if ($descripcion != null && $foto != null) {
                    $stmt->bind_param('sdssi', $nombre, $precio, $descripcion, $foto, $idCategoria);
                } else if ($descripcion != null) {
                    $stmt->bind_param('sdsi', $nombre, $precio, $descripcion, $idCategoria);
                } else if ($foto != null) {
                    $stmt->bind_param('sdsi', $nombre, $precio, $foto, $idCategoria);
                } else {
                    $stmt->bind_param('sdi', $nombre, $precio, $idCategoria);
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

    function eliminarProducto($id): void
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call eliminarProducto(?);";
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

    function buscarProducto(string $palabra): array
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        $resultado = [];
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call buscarProducto(?, inicio(), fin())";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('s', $palabra);
                $stmt->execute();
                $stmt->bind_result($id, $Nombre, $Precio, $Descripcion, $Foto, $idCategoria);
                while ($stmt->fetch()) {
                    $resultado[count($resultado)] = new producto((int) $id, (string) $Nombre, (float) $Precio, (string) $Descripcion, (string) $Foto, new Categoria((int) $idCategoria, "", ""));
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

    function buscarProductoPorId(int $id): producto
    {
        $conexion = new conexion('', '', '', '');
        $this->conn = $conexion->getConexion();
        $resultado = null;
        if (!$this->conn->connect_errno) {
            try {
                $this->conn->autocommit(false);
                $this->conn->begin_transaction();
                $sql = "call buscarProductoPorId(?);";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->bind_result($id, $Nombre, $Precio, $Descripcion, $Foto, $idCategoria);
                while ($stmt->fetch()) {
                    $resultado = new producto((int) $id, (string) $Nombre, (float) $Precio, (string) $Descripcion, (string) $Foto, new Categoria((int) $idCategoria, "", ""));
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
