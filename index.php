<?php

session_start();

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

$httpMethod = $_SERVER['REQUEST_METHOD'];

if ($httpMethod == 'GET' && $path == 'register') {
    require_once 'src/controllers/RegistrationController.php';
    $controller = new RegistrationController();
    $controller->showRegistrationForm();
} elseif ($httpMethod == 'POST' && $path == 'register') {
    require_once 'src/controllers/RegistrationController.php';
    $controller = new RegistrationController();
    $controller->register();
} else {
    Router::get('', 'DefaultController');
    Router::get('catalog', 'DefaultController');
    Router::post('login', 'SecurityController');
    Router::get('logout', 'SecurityController');
    Router::post('addBook', 'BookController');
    Router::run($path);
}