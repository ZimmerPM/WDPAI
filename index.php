<?php

session_start();

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('catalog', 'DefaultController');

Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');

if ($path === 'register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new RegisterController();
    $controller->registerForm();
} elseif ($path === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RegisterController();
    $controller->register();
}


Router::post('addBook', 'BookController');

Router::run($path);