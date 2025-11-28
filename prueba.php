<?php
if (!defined('CONFIG')) require_once './config.php';

/*echo HOST_ROOT . " :Este es el valor de la constante." . "<hr>";
echo $_SERVER['SERVER_NAME'] . " :Este es el valor de server name" . "<hr>";
echo $_SERVER['DOCUMENT_ROOT'] . " :Este el valor de document root." . "<hr>";
echo __DIR__ . " :Este es el valor de __DIR__" . "<hr>";
//echo explode("/", $_SERVER['SERVER_NAME']);
$prueba =  "hola mundo";
$tremano = str_replace( //puedo obtener la otra parte de mi host raiz de la variable __DIR__ pero esta me trae toda la ruta, y solo nesecito de htdocs para adelante
    str_replace('/', '\\', $_SERVER['DOCUMENT_ROOT'] . '/'), //obtengo la parte del strin que le voy a quitar a la ruta completa para usar
    '',
    __DIR__ //me entrega la ruta completa de donde estan guardado el archivo en donde se esta ejecutando esto
);
echo mb_strlen($tremano) . "<hr>";
echo (__DIR__ != $_SERVER['DOCUMENT_ROOT']) . " :resultado";
*/
echo $_SERVER['SERVER_NAME'] . " :Este es el valor de server name" . "<hr>";
echo $_SERVER['DOCUMENT_ROOT'] . " :Este el valor de document root." . "<hr>";
echo HOST_ROOT . " :Este es el valor de la constante." . "<hr>";
echo __DIR__ . " :Este es el valor de __DIR__" . "<hr>";
echo "Pagina de inicio" . HOMEPAGE . "<hr>";
echo "las siguientes son las rutas disponibles <hr>";
echo "host_sql: " . SQL_HOST . "<hr>";
echo "usuario_sql: " . SQL_USER . "<hr>";
echo "Password_sql: " . SQL_PASS . "<hr>";
echo "dbname_sql" . SQL_DBNAME . "<hr>";
foreach (ROUTE_DIR_EXIST as $ruta) {
    echo $ruta . "<br>";
}
