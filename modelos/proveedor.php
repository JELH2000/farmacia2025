<?php
class proveedor
{
    private $idProveedor;
    private $Nombre;
    private $Telefono;
    private $Direccion;

    function __construct(int $id, string $nombre, string $telefono, string $direccion)
    {
        $this->idProveedor = $id;
        $this->Nombre = $nombre;
        $this->Telefono = $telefono;
        $this->Direccion = $direccion;
    }

    function getIdProveedor(): int
    {
        return $this->idProveedor;
    }
    function setIdProveedor(int $id): void
    {
        $this->idProveedor = $id;
    }

    function getNombre(): string
    {
        return $this->Nombre;
    }

    function setNombre(string $nombre): void
    {
        $this->Nombre = $nombre;
    }

    function getTelefono(): string
    {
        return $this->Telefono;
    }

    function setTelefono(string $telefono): void
    {
        $this->Telefono = $telefono;
    }

    function getDireccion(): string
    {
        return $this->Direccion;
    }

    function setDireccion(string $direccion): void
    {
        $this->Direccion = $direccion;
    }
}
