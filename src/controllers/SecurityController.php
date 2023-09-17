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

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Niepoprawne hasło!']]);
        }

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
        unset($_SESSION['user']);
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

    public function changePassword()
    {
        $userRepository = new UserRepository();

        $response = ['success' => false]; // domyślna wartość

        if (!$this->isPost()) {
            $response['message'] = 'Invalid request method.';
            $this->sendJsonResponse($response);
            return;
        }

        $email = $_SESSION['user']['email'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $repeatPassword = $_POST['repeatPassword'];

        $user = $userRepository->getUser($email);

        if (!password_verify($currentPassword, $user->getPassword())) {
            $response['message'] = 'Obecne hasło jest niepoprawne!';
            $this->sendJsonResponse($response);
            return;
        }

        if ($newPassword !== $repeatPassword) {
            $response['message'] = 'Nowe hasła nie są takie same!';
            $this->sendJsonResponse($response);
            return;
        }

        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userRepository->updatePassword($email, $newHashedPassword);

        $response['success'] = true;
        $response['message'] = 'Hasło zostało zmienione!';
        $this->sendJsonResponse($response);
    }

    private function sendJsonResponse(array $response): void
    {
        header('Content-Type: application/json');
        echo json_encode($response);
    }

}