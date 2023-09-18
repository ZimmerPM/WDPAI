<?php

require_once __DIR__.'/../repository/BookRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class AdminController extends BookController

{
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

}
