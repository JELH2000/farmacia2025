<?php
class categoria
{
    private $idCategoria;
    private $Nombre;
    private $Descripcion;

    function __construct(int $idCategoria, string $nombre, string $descripcion)
    {
        $this->idCategoria = $idCategoria;
        $this->Nombre = $nombre;
        $this->Descripcion = $descripcion;
    }

    function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

    function setIdCategoria(int $id): void
    {
        $this->idCategoria = $id;
    }

    function getNombre(): string
    {
        return $this->Nombre;
    }
    function setNombre(string $nombre): void
    {
        $this->Nombre = $nombre;
    }
    function getDescripcion(): string
    {
        return $this->Descripcion;
    }
    function setDescripcion(string $descripcion): void
    {
        $this->Descripcion = $descripcion;
    }
}
