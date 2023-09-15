<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController
{
    public function login()
    {
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];


        $user = $userRepository->getUser($email);

        if (!$user)
        {
            return $this->render('login', ['messages' => ['Nie ma takiego użytkownika!']]);
        }

        // Sprawdzanie hasła:
        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Niepoprawne hasło!']]);
        }

        // Jeśli e-mail i hasło są prawidłowe, zapisz dane w sesji i przekieruj użytkownika.
        $_SESSION['user'] = [
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRole()
        ];

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/catalog");
    }


    public function logout()
    {
        // Usuń dane użytkownika z sesji:
        unset($_SESSION['user']);

        // Przekieruj użytkownika do strony logowania (lub innej strony startowej):
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }


    public function profile()
    {
        if ($this->isLoggedIn())
        {
            return $this->render('profile');
        }
    }

}

