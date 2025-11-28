<?php
if (!defined('DIRCONFIG')) {
    require_once dirname(__DIR__) . '/dirConfig.php';
}
if (!defined('CONFIG')) {
    require_once dirname(__DIR__) . '/config.php';
}

class conexion
{
    private $connect;

    function __construct(string $host, string $user, string $pass, string $dbname)
    {
        $this->connect = new mysqli(
            SQL_HOST,
            SQL_USER,
            SQL_PASS,
            SQL_DBNAME
        );
    }

    function getConexion(): mysqli
    {
        return $this->connect;
    }
}
