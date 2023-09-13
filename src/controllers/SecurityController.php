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

        // Sprawdzanie adresu e-mail:
        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['Brak użytkownika o podanym adresie e-mail!']]);
        }

        // Sprawdzanie hasła:
        if ($user->getPassword() !== $password) {
            return $this->render('login', ['messages' => ['Niepoprawne hasło!']]);
        }

        // Jeśli e-mail i hasło są prawidłowe, zapisz dane w sesji i przekieruj użytkownika.
        $_SESSION['user'] = [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ];

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/catalog");
    }

    public function logout() {
        // Usuń dane użytkownika z sesji:
        unset($_SESSION['user']);

        // Przekieruj użytkownika do strony logowania (lub innej strony startowej):
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}

