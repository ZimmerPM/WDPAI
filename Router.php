<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/BookController.php';
require_once 'src/controllers/RegistrationController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/BorrowController.php';

class Router 
{

    public static $routes;
    
    public static function get($url, $view)
    {
        self::$routes[$url] = $view;
    }

    public static function post($url, $view)
    {
        self::$routes[$url] = $view;
    }

    public static function run($url)
    {
        $action = explode("/", $url)[0];
        
        if (!array_key_exists($action, self::$routes))
        {
            die("Wrong url! This path goes nowhere!" );
        }
        
        $controller = self::$routes[$action];
        $object = new $controller;
        $action = $action ?: 'index';

        $object->$action();
    }
}
