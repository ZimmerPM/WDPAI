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

    Router::post('login', 'SecurityController');
    Router::get('logout', 'SecurityController');

    Router::get('profile', 'SecurityController');
    Router::post('changePassword', 'SecurityController');

    Router::post('catalog', 'BookController');
    Router::post('search', 'BookController');

    Router::get('adminPanel', 'AdminController');
    Router::get('usersManagement', 'AdminController');

    Router::post('addBook', 'AdminController');
    Router::post('editBook', 'AdminController');
    Router::post('removeBook', 'AdminController');

    Router::post('editUser','AdminController');
    Router::post('removeUser','AdminController');

    Router::get('loans', 'BorrowController');
    Router::post('borrow', 'BorrowController');

    Router::get('reservations', 'BorrowController');
    Router::post('reserve', 'BorrowController');
    Router::post('cancelReservation', 'BorrowController');
    Router::post('adminCancelReservation', 'BorrowController');
    Router::post('lendBook', 'BorrowController');

    Router::post('cancelLoan', 'BorrowController');
    Router::post('returnBook', 'BorrowController');
    Router::get('loansArchive', 'BorrowController');



    Router::run($path);
}