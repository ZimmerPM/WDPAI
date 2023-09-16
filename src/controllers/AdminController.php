<?php

require_once 'BookController.php';

class AdminController extends BookController

{
    public function adminPanel()
    {
        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        $bookRepository = new BookRepository();
        $books = $bookRepository->getBooks();

        return $this->render('admin-Panel', ['books' => $books]);

    }

}
