<?php

class RegisterController extends AppController
{

    public function registerForm()
    {
        return $this->render('register');
    }
    public function register()
    {
        $userRepository = new UserRepository();

        if ($this->isPost()) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];

            // Hashuj hasło
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Tutaj przekazujesz zahaszowane hasło
            $user = new User($email, $hashedPassword, $name, $lastname, 'user');

            // Teraz zapisz użytkownika w bazie danych. (Dopisz tę funkcję w UserRepository)

            $userRepository->save($user);

            // Przekieruj do logowania
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }

        return $this->render('register');
    }
}
