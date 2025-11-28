<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}

require_once MODELOS . '/producto.php';
require_once MODELOS . '/proveedor.php';
require_once RECURSOS . '/fecha.php';

class importe
{
    private $idImporte;
    private $Vencimiento;
    private $FechaCompra;
    private $CodigoProducto;
    private $Estado;
    private $Producto;
    private $Proveedor;

    function __construct(int $id, fecha $vencimiento, fecha $fechaCompra, int $codigoProducto, string $estado, proveedor $proveedor, producto $producto)
    {
        $this->idImporte = $id;
        $this->Vencimiento = $vencimiento;
        $this->FechaCompra = $fechaCompra;
        $this->CodigoProducto = $codigoProducto;
        $this->Estado = $estado;
        $this->Proveedor = $proveedor;
        $this->Producto = $producto;
    }

    function getIdImporte(): int
    {
        return $this->idImporte;
    }

    function setIdImporte(int $id): void
    {
        $this->idImporte = $id;
    }

    function getVencimiento(): fecha
    {
        return $this->Vencimiento;
    }

    function setVencimiento(fecha $vencimiento): void
    {
        $this->Vencimiento = $vencimiento;
    }

    function getFechaCompra(): fecha
    {
        return $this->FechaCompra;
    }

    function setFechaCompra(fecha $fechaCompra): void
    {
        $this->FechaCompra = $fechaCompra;
    }

    function getCodigoProducto(): int
    {
        return $this->CodigoProducto;
    }

    function setCodigoProducto(int $codigoProducto): void
    {
        $this->CodigoProducto = $codigoProducto;
    }

    function getEstado(): string
    {
        return $this->Estado;
    }

    function setEstado(string $estado): void
    {
        $this->Estado = $estado;
    }

    function getProducto(): producto
    {
        return $this->Producto;
    }

    function setProducto(producto $producto): void
    {
        $this->Producto = $producto;
    }

    function getProveedor(): proveedor
    {
        return $this->Proveedor;
    }

    function setProveedor(proveedor $proveedor): void
    {
        $this->Proveedor = $proveedor;
    }
}
