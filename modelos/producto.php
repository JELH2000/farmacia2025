<?php
if (!defined('DIRCONFIG')) require_once dirname(__DIR__) . '/dirConfig.php';

require_once MODELOS . '/categoria.php';

class producto
{
    private $idProducto;
    private $Nombre;
    private $Precio;
    private $Descripcion;
    private $Foto;
    private $Categoria;

    function __construct(int $id, string $nombre, float $precio, string $descripcion, string $foto, categoria $categoria)
    {
        $this->idProducto = $id;
        $this->Nombre = $nombre;
        $this->Precio = $precio;
        $this->Descripcion = $descripcion;
        $this->Foto = $foto;
        $this->Categoria = $categoria;
    }

    function getIdProducto(): int
    {
        return $this->idProducto;
    }

    function setIdProducto(int $id): void
    {
        $this->idProducto = $id;
    }

    function getNombre(): string
    {
        return $this->Nombre;
    }

    function setNombre(string $nombre): void
    {
        $this->Nombre = $nombre;
    }

    function getPrecio(): float
    {
        return $this->Precio;
    }

    function setPrecio(float $precio): void
    {
        $this->Precio = $precio;
    }

    function getDescripcion(): string
    {
        return $this->Descripcion;
    }

    function setDescripcion(string $descripcion): void
    {
        $this->Descripcion = $descripcion;
    }

    function getFoto(): string
    {
        return $this->Foto;
    }

    function setFoto(string $foto): void
    {
        $this->Foto = $foto;
    }

    function getCategoria(): categoria
    {
        return $this->Categoria;
    }
}
