<?php
if (!defined('DIRCONFIG')) {
    require_once './dirConfig.php';
}

if (!defined('SQL_HOST')) {
    define('SQL_HOST', 'hopper.proxy.rlwy.net:54056');
}

if (!defined('SQL_USER')) {
    define('SQL_USER', 'root');
}

if (!defined('SQL_PASS')) {
    define('SQL_PASS', 'QZvNxmNRDUqxWxIWQsljnPWRfcHKvIgZ');
}

if (!defined('SQL_DBNAME')) {
    define('SQL_DBNAME', 'farmacia');
}

if (!defined('PASSWORD_HASH_ENCRIPT')) {
    define('PASSWORD_HASH_ENCRIPT', '19b8(PJ7?F$C');
}

if (!defined('ROUTE_DIR_EXIST')) {
    define('ROUTE_DIR_EXIST', getDirectoryTree(__DIR__));
}

if (!defined('CONFIG')) {
    define('CONFIG', true);
}

if (!defined('HOMEPAGE')) {
    define('HOMEPAGE', 'vistas/home.php'); // Cambié a home.php para el dashboard
}

if (!defined('FILE_PRIVATE')) {
    define(
        'FILE_PRIVATE',
        []
    );
}

if (!defined('FILE_UNAUTHORIZED')) { //no permitidos
    define(
        'FILE_UNAUTHORIZED',
        [
            '.htaccess',
            'config.php',
            'dirConfig.php',
            'recursos/hasCripting.php',
            'recursos/fecha.php',
            'controladores',
            'modelos',
            'modelos/categoria.php',
            'modelos/detalleventa.php',
            'modelos/empleado.php',
            'modelos/importe.php',
            'modelos/producto.php',
            'modelos/proveedor.php',
            'controladores/ImporteController.php',
            'controladores/ProveedorController.php'
        ]
    );
}

if (!defined('EXTENTION_FILE')) {
    define(
        'EXTENTION_FILE',
        [
            'js' => 'text/javascript',
            'css' => 'text/css',
            'html' => 'text/html',
            'png' => 'image/png',
            'php' => 'text/html',
            'jpg' => 'image/jpeg',
            'pdf' => 'application/pdf',
            "json" => "application/json",
            "txt" => "application/txt",
            "ico" => "image/x-icon"
        ]
    );
}

if (!defined('HOST_ROOT')) {
    define('HOST_ROOT',  "https://farmacia2025-production.up.railway.app");
}

/*
if (!defined('HOST_ROOT')) {

    if (__DIR__ != $_SERVER['DOCUMENT_ROOT']) {
        define(
            'HOST_ROOT', //Defino la constante con la cual encontrare mi host raiz
            $_SERVER['SERVER_NAME'] . '/' . //obtengo el nombre del host del servidor hospedado
                str_replace( //puedo obtener la otra parte de mi host raiz de la variable __DIR__ pero esta me trae toda la ruta, y solo nesecito de htdocs para adelante
                    str_replace('/', '\\', $_SERVER['DOCUMENT_ROOT'] . '/'), //obtengo la parte del strin que le voy a quitar a la ruta completa para usar
                    '',
                    __DIR__ //me entrega la ruta completa de donde estan guardado el archivo en donde se esta ejecutando esto
                )
        ); //al finalizar toda la operacion obtendre el host raiz
    } else {
        define('HOST_ROOT', $_SERVER['SERVER_NAME']);
    }
}*/

// Constantes para el menú y navegación
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Sistema Farmacia');
}

if (!defined('APP_VERSION')) {
    define('APP_VERSION', '1.0.0');
}

// Constantes para los módulos del sistema
if (!defined('MODULOS')) {
    define('MODULOS', [
        'dashboard' => [
            'nombre' => 'Dashboard',
            'archivo' => 'home.php',
            'icono' => 'bi-speedometer2'
        ],
        'proveedores' => [
            'nombre' => 'Proveedores',
            'archivo' => 'proveedores.php',
            'icono' => 'bi-truck'
        ],
        'importes' => [
            'nombre' => 'Importes',
            'archivo' => 'importes.php',
            'icono' => 'bi-box-seam'
        ],
        'productos' => [
            'nombre' => 'Productos',
            'archivo' => 'productos.php',
            'icono' => 'bi-capsule'
        ],
        'categorias' => [
            'nombre' => 'Categorías',
            'archivo' => 'categorias.php',
            'icono' => 'bi-tags'
        ],
        'ventas' => [
            'nombre' => 'Ventas',
            'archivo' => 'ventas.php',
            'icono' => 'bi-cart-check'
        ],
        'empleados' => [
            'nombre' => 'Empleados',
            'archivo' => 'empleados.php',
            'icono' => 'bi-people'
        ]
    ]);
}

function autorizeAcces(string $file)
{
    return true;
}

function getDirectoryTree(string $dir)
{
    $tree = [];
    $items = scandir($dir);

    if ($items == false) {
        return []; // O maneja el error de otra manera
    }

    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $path = ($dir != './' ? $dir : '.') . '/' . $item;
            if (is_dir($path)) {
                $temp = getDirectoryTree($path);
                if (count($temp) > 0) {
                    foreach ($temp as $itemR) {
                        $tree[count($tree)] = $item . '/' . $itemR;
                    }
                    $tree[count($tree)] = $item;
                } else {
                    $tree[count($tree)] = $item;
                }
            } else {
                $tree[count($tree)] = $item;
            }
        }
    }
    return $tree;
}

// Función auxiliar para generar URLs
function generateUrl($archivo)
{
    return 'http://' . HOST_ROOT . '/vistas/' . $archivo;
}

// Función para verificar si es la página actual
function isActivePage($pagina_actual, $modulo)
{
    return $pagina_actual === $modulo;
}
