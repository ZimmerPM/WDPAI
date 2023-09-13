<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController
{
    public function login()
    {
        $user = new User('annanowak@poczta.pl', 'pass', 'Anna', 'Nowak');

        if (!$this->isPost()) {
            return $this->render('login');
        }
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user->getEmail() !== $email)
        {
            return $this->render('login',['messages' => ['Brak użytkownika o podanym adresie e-mail!']]);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/catalog");
    }
}

