<?php

session_start();

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('catalog', 'DefaultController');

Router::post('login', 'SecurityController');
Router::get('logout', 'SecurityController');

Router::post('addBook', 'BookController');

Router::run($path);