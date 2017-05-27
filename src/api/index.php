<?php
function autoloader($class) {
    require 'classes/' . $class . '.php';
}

ini_set('display_errors', true);
error_reporting(-1);
spl_autoload_register('autoloader');

$router = [
    'POST' => [
        '/api/print/' => function () {
            $controller = new PrinterController(
                new ShellAccess(),
                new PhotoCreator()
            );

            $response = $controller->print(new Request($_POST));
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    ]
];

if (isset($router[$_SERVER['REQUEST_METHOD']][$_SERVER['REQUEST_URI']])) {
    $router[$_SERVER['REQUEST_METHOD']][$_SERVER['REQUEST_URI']]();
} else {
    header('HTTP/1.0 404 Not Found');
}