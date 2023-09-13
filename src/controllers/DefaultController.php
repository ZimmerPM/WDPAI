<?php

require_once 'AppController.php';

class DefaultController extends AppController 
{
    public function index()

    {
        $this->render('login');

    }

    public function catalog()

    {
        if (!isset($_SESSION['user']))
        {
            return $this->render('login', ['messages' => ['Użytkownik niezalogowany. Proszę się najpierw zalogować!']]);
        }

        $this->render('catalog');
    }
}