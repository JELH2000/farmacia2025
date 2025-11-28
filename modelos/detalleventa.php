<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}

require_once MODELOS . '/empleado.php';
require_once MODELOS . '/producto.php';
require_once RECURSOS . '/fecha.php';

class detalleventa
{
    private $idDetalle;
    private $Fecha;
    private $Descuentos;
    private $TipoPago;
    private $Total;
    private $Empleado;
    private $Productos;

    function __construct(int $id, fecha $fecha, float $descuentos, string $tipoPago, float $total, empleado $empleado, array $productos)
    {
        $this->idDetalle = $id;
        $this->Fecha = $fecha;
        $this->Descuentos = $descuentos;
        $this->TipoPago = $tipoPago;
        $this->Total = $total;
        $this->Empleado = $empleado;
        $this->Productos = $productos;
    }

    function getIdDetalle(): int
    {
        return $this->idDetalle;
    }

    function setIdDetalle(int $id): void
    {
        $this->idDetalle = $id;
    }

    function getFecha(): fecha
    {
        return $this->Fecha;
    }

    function setFecha(fecha $fecha): void
    {
        $this->Fecha = $fecha;
    }

    function getDescuentos(): float
    {
        return $this->Descuentos;
    }

    function setDescuentos(float $descuentos): void
    {
        $this->Descuentos = $descuentos;
    }

    function getTipoPago(): string
    {
        return $this->TipoPago;
    }

    function setTipoPago(string $tipoPago): void
    {
        $this->TipoPago = $tipoPago;
    }

    function getTotal(): float
    {
        return $this->Total;
    }

    function setTotal(float $total): void
    {
        $this->Total = $total;
    }

    function getEmpleado(): empleado
    {
        return $this->Empleado;
    }

    function setEmpleado(empleado $empleado): void
    {
        $this->Empleado = $empleado;
    }

    function getProductos(): array
    {
        return $this->Productos;
    }

    function setProductos(array $productos): void
    {
        $this->Productos = $productos;
    }
}
