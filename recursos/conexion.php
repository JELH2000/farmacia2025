<?php
if (!defined('CONFIG')) require_once dirname(__DIR__) . '/config.php';

class conexion
{
    private $connect;

    function __construct(string $host, string $user, string $pass, string $dbname)
    {
        $this->connect = new mysqli(
            ($host != '' ? $host : SQL_HOST),
            ($user != '' ? $user : SQL_USER),
            ($dbname != '' ? $pass : SQL_PASS),
            ($dbname != '' ? $dbname : SQL_DBNAME)
        );
    }

    function getConexion(): mysqli
    {
        return $this->connect;
    }
}
