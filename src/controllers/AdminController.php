<?php

require_once 'BookController.php';
require_once __DIR__.'/../repository/BookRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class AdminController extends BookController

{
    private $message = [];

    public function adminPanel()
    {
        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        $bookRepository = new BookRepository();
        $books = $bookRepository->getBooks();

        return $this->render('admin-panel', ['books' => $books]);

    }

    public function usersManagement()
    {
        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        $userRepository = new UserRepository();
        $users = $userRepository->getAllUsers();

        return $this->render('users-management', ['users' => $users]);;
    }

    public function editUser()
    {
       // echo "Funkcja editUser została wywołana.<br>";
        $response = [];

        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        if (!isset($_POST['userId']) || empty($_POST['userId'])) {
            die("Błąd: ID użytkownika nie zostało przesłane!");
        }

        if ($this->isPost()) {
           // echo "Żądanie jest typu POST.<br>";
            $userId = $_POST['userId'];
            $email = $_POST['email'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $role = $_POST['role'];


            $user = new User(
                $userId,
                $email,
                null,  // Nie aktualizujemy hasła w tej funkcji
                $name,
                $lastname,
                $role
            );

            $userRepository = new UserRepository();

            try {
             //   echo "Przed wywołaniem metody updateUser.<br>";
                $userRepository->updateUser($user);
              //  echo "Po wywołaniu metody updateUser.<br>";
                $response['status'] = 'success';
                $response['message'] = 'Dane użytkownika zostały zaktualizowane';
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = "Wystąpił błąd podczas aktualizacji: " . $e->getMessage();
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = $this->message ?: 'Nieprawidłowe żądanie!';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function removeUser()
    {
        $response = [];

        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        if ($this->isPost()) {
            // Odczytanie surowych danych POST w formacie JSON
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, true);
            $userId = $input['id'];

            $userRepository = new UserRepository();
            $result = $userRepository->deleteUser($userId);

            if ($result) { // Jeśli operacja usuwania się powiodła
                $response['status'] = 'success';
                $response['message'] = 'Użytkownik został usunięty pomyślnie.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Wystąpił błąd podczas usuwania użytkownika!';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Nieprawidłowe żądanie!';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }


}
