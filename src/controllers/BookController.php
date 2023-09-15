<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Book.php';
require_once __DIR__.'/../repository/BookRepository.php';

class BookController extends AppController
{
    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpg', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $message = [];

    public function addBook()
    {
        if ($this->isPost()) {
            if (is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file'])) {
                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file']['name']
                );

                $stock = $_POST['stock'];
                $availability = $stock > 0 ? true : false;

                $book = new Book(
                    $_POST['author'],
                    $_POST['title'],
                    $_POST['publicationyear'],
                    $_POST['genre'],
                    $availability,
                    $stock,
                    $_FILES['file']['name']
                );

                // Tu ewentualnie można dodać logikę zapisu książki do bazy danych

                return $this->render('catalog', ['messages' => $this->message, 'book' => $book]);
            } else {
                // Jeśli walidacja nie powiodła się, ponownie renderuj formularz
                return $this->render('add-book', ['messages' => $this->message]);
            }
        }
        return $this->render('add-book', ['messages' => $this->message]);
    }

    private function validate(array $file): bool
    {

        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->message[] = 'Plik jest zbyt duży!';
            return false;
        }
        if (!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES)) {
            $this->message[] = 'Nieprawidłowy format pliku!';
            return false;
        }
        return true;
    }

    public function catalog()
    {
        $bookRepository = new BookRepository();
        $books = $bookRepository->getBooks();

        return $this->render('catalog', ['books' => $books]);

    }
    public function search()
    {
        header('Content-type: application/json');
        $response = [];

        if ($this->isPost()) {
            $inputData = json_decode(file_get_contents('php://input'), true);
            $searchTerm = $inputData['query'];

            $bookRepository = new BookRepository();
            $books = $bookRepository->searchBooks($searchTerm);

            foreach ($books as $book) {
                $response[] = [
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthor(),
                    'publicationYear' => $book->getPublicationYear(),
                    'genre' => $book->getGenre(),
                    'availability' => $book->isAvailable() ? 'Dostępna' : 'Niedostępna',
                    'stock' => $book->getStock(),
                    'image' => $book->getImage()
                ];
            }
        }
        echo json_encode($response);
    }
}