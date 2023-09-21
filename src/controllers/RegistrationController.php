<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class RegistrationController extends AppController
{
    public function showRegistrationForm()
    {
        $this->render('register');
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $rawPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        // Podstawowa walidacja - może być rozbudowana o dodatkowe kryteria
        if (empty($name) || empty($lastname) || empty($email) || empty($rawPassword)) {
            return $this->render('register', ['messages' => ['Wypełnij wszystkie pola!']]);
        }

        if ($rawPassword !== $confirmPassword) {
            return $this->render('register', ['messages' => ['Hasła nie są takie same!']]);
        }

        $password = password_hash($rawPassword, PASSWORD_DEFAULT);

        $userRepository = new UserRepository();

        // Sprawdzanie, czy użytkownik o danym adresie e-mail już istnieje
        if ($userRepository->getUser($email)) {
            return $this->render('register', ['messages' => ['Użytkownik o takim adresie e-mail już istnieje!']]);
        }

        // Zapis nowego użytkownika w bazie danych
        $userRepository->addUser(new User(null, $email, $password, $name, $lastname, 'user'));

        // Po udanej rejestracji przekierowujemy użytkownika do strony logowania
        $url = "http://$_SERVER[HTTP_HOST]/login";
        header("Location: {$url}");
    }
}
