<?php
class empleado
{
    private $idEmpleado;
    private $Nombre;
    private $Usuario;
    private $Contrasenia;
    private $Foto;
    private $Estado;

    function __construct(int $id, string $nombre, string $usuario, string $contrasenia, string $foto, string $estado)
    {
        $this->idEmpleado = $id;
        $this->Nombre = $nombre;
        $this->Usuario = $usuario;
        $this->Contrasenia = $contrasenia;
        $this->Foto = $foto;
        $this->Estado = $estado;
    }

    function getIdEmpleado(): int
    {
        return $this->idEmpleado;
    }

    function setIdEmpleado(int $id): void
    {
        $this->idEmpleado = $id;
    }

    function getNombre(): string
    {
        return $this->Nombre;
    }

    function setNombre(string $nombre): void
    {
        $this->Nombre = $nombre;
    }

    function getUsuario(): string
    {
        return $this->Usuario;
    }

    function setUsuario(string $usuario): void
    {
        $this->Usuario = $usuario;
    }

    function getContrasenia(): string
    {
        return $this->Contrasenia;
    }

    function setContrasenia(string $contrasenia): void
    {
        $this->Contrasenia = $contrasenia;
    }

    function getFoto(): string
    {
        return $this->Foto;
    }

    function setFoto(string $foto): void
    {
        $this->Foto = $foto;
    }

    function getEstado(): string
    {
        return $this->Estado;
    }

    function setEstado(string $estado): void
    {
        $this->Estado = $estado;
    }
}
