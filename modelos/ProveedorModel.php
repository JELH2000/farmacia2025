<?php
class ProveedorModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertar($nombre, $telefono, $direccion) {
        $stmt = $this->conn->prepare("CALL insertarProveedor(?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $telefono, $direccion);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function actualizar($id, $nombre, $telefono, $direccion) {
        $stmt = $this->conn->prepare("CALL actualizarProveedor(?, ?, ?, ?)");
        $stmt->bind_param("isss", $id, $nombre, $telefono, $direccion);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("CALL eliminarProveedor(?)");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("CALL proveedorPorId(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $proveedor = $result->fetch_assoc();
        $stmt->close();
        return $proveedor;
    }

    public function listar($desde = -1, $hasta = -1) {
        $stmt = $this->conn->prepare("CALL obtenerProveedores(?, ?)");
        $stmt->bind_param("ii", $desde, $hasta);
        $stmt->execute();
        $result = $stmt->get_result();
        $proveedores = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $proveedores;
    }

    public function buscar($palabra, $desde = -1, $hasta = -1) {
        $stmt = $this->conn->prepare("CALL buscarProveedor(?, ?, ?)");
        $stmt->bind_param("sii", $palabra, $desde, $hasta);
        $stmt->execute();
        $result = $stmt->get_result();
        $proveedores = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $proveedores;
    }
}
?>