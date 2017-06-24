<?php
ini_set('display_errors', true);
error_reporting(-1);
spl_autoload_register(function ($class) {
    require 'classes/' . $class . '.php';
});

$debug = substr($_SERVER['SERVER_ADDR'], 0, 7) !== '10.5.5.';

$router = [
    'POST' => [
        '/api/take/' => function () use ($debug) {
            $controller = new TakePhotoController($debug);
            $controller->take()->output();
        },
        '/api/fetch/' => function () use ($debug) {
            $controller = new FetchController($debug);
            $controller->fetch()->output();
        },
        '/api/print/' => function () use ($debug) {
            $controller = new PrinterController(
                new ShellAccess(),
                new MergedPhotoCreator(),
                $debug
            );
            $controller->print(new Request($_POST))->output();
        }
    ],
    'GET' => [
        '/api/photo/' => function () use ($debug) {
            $controller = new PhotoController($debug);
            $controller->stream($_GET['photo'])->output();
        }
    ]
];

$queryMark = strpos($_SERVER['REQUEST_URI'], '?');

if ($queryMark === false) {
    $requestUri = $_SERVER['REQUEST_URI'];
} else {
    $requestUri = substr($_SERVER['REQUEST_URI'], 0, $queryMark);
}

if (isset($router[$_SERVER['REQUEST_METHOD']][$requestUri])) {
    $router[$_SERVER['REQUEST_METHOD']][$requestUri]();
} else {
    header('HTTP/1.0 404 Not Found');
}