<?php
class ImporteModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertar($vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor) {
        $stmt = $this->conn->prepare("CALL insertarImporte(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisii", $vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function actualizar($id, $vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor) {
        $stmt = $this->conn->prepare("CALL actualizarImporte(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issisii", $id, $vencimiento, $fechaCompra, $codigoProducto, $estado, $fkProducto, $fkProveedor);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("CALL eliminarImporte(?)");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM importe WHERE idImporte = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $importe = $result->fetch_assoc();
        $stmt->close();
        return $importe;
    }

    public function listar($desde = -1, $hasta = -1) {
        // Usamos un query directo ya que no hay procedimiento para listar todos
        $sql = "SELECT i.*, p.Nombre as producto_nombre, pr.Nombre as proveedor_nombre 
                FROM importe i 
                LEFT JOIN producto p ON i.fkProducto = p.idProducto 
                LEFT JOIN proveedor pr ON i.fkProveedor = pr.idProveedor 
                ORDER BY i.idImporte DESC";
        
        if ($desde >= 0 && $hasta >= 0) {
            $sql .= " LIMIT $desde, $hasta";
        }
        
        $result = $this->conn->query($sql);
        $importes = $result->fetch_all(MYSQLI_ASSOC);
        return $importes;
    }

    public function buscar($palabra, $desde = -1, $hasta = -1) {
        $stmt = $this->conn->prepare("
            SELECT i.*, p.Nombre as producto_nombre, pr.Nombre as proveedor_nombre 
            FROM importe i 
            LEFT JOIN producto p ON i.fkProducto = p.idProducto 
            LEFT JOIN proveedor pr ON i.fkProveedor = pr.idProveedor 
            WHERE i.CodigoProducto LIKE ? OR i.Estado LIKE ? OR p.Nombre LIKE ? OR pr.Nombre LIKE ?
            ORDER BY i.idImporte DESC
        ");
        $searchTerm = "%$palabra%";
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $importes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $importes;
    }

    public function obtenerProductos() {
        $result = $this->conn->query("SELECT idProducto, Nombre FROM producto ORDER BY Nombre");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerProveedores() {
        $result = $this->conn->query("SELECT idProveedor, Nombre FROM proveedor ORDER BY Nombre");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>