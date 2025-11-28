<?php
if (!defined('CONFIG')) {
    require_once './config.php';
}


if (isset($_GET['url'])) if ($_GET['url'] == '' || $_GET['url'] == 'index.php') {
    header('Location: ' . HOMEPAGE);
    exit();
}

$url = $_GET['url'];
echo $url;
/*
$urlExist = in_array($url, ROUTE_DIR_EXIST);
$estaRestringido = in_array($url, FILE_UNAUTHORIZED);

if ($urlExist) {
    if (!is_dir(__DIR__ . '/' . $url)) header('Content-Type:' . EXTENTION_FILE[pathinfo($url, PATHINFO_EXTENSION)]);
    if (!$estaRestringido) {
        if (!is_dir(__DIR__ . '/' . $url)) {
            if ($url != HOMEPAGE ? autorizeAcces($url) : true) {
                require_once __DIR__ . '/' . $url;
                exit();
            } else {
                header('Location: ' . 'http://' . HOST_ROOT . '/' . HOMEPAGE);
                exit();
            }
        } else {
            header('Location: ' . 'http://' . HOST_ROOT . '/' . HOMEPAGE);
            exit();
        }
    } else {
        header('Location: ' . 'http://' . HOST_ROOT . '/' . 'vistas/error/page403.html');
        exit();
    }
} else {
    header('Location: ' . 'http://' . HOST_ROOT . '/' . 'vistas/error/page404.html');
    exit();
}
*/