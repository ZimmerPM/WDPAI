<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Book.php';
require_once __DIR__.'/../repository/BookRepository.php';


class BookController extends AppController
{
    const MAX_FILE_SIZE = 1024 * 1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpg', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $message = [];


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
                $response['books'][] = [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'author' => $book->getAuthor(),
                    'publicationyear' => $book->getPublicationYear(),
                    'genre' => $book->getGenre(),
                    'availability' => $book->isAvailable() ? 'Dostępna' : 'Niedostępna',
                    'stock' => $book->getStock(),
                    'image' => $book->getImage()
                ];
            }

            $response['isLoggedIn'] = isset($_SESSION['user']);

            if (isset($_SESSION['user'])) {
                $response['role'] = $_SESSION['user']['role'];
            }
        }

        echo json_encode($response);
    }


    public function addBook()
    {
        $response = [];

        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        if ($this->isPost()) {
            if (is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file'])) {
                move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    dirname(__DIR__) . self::UPLOAD_DIRECTORY . $_FILES['file']['name']
                );

                $stock = $_POST['stock'];
                $availability = $stock > 0 ? true : false;

                $book = new Book(
                    null,
                    $_POST['author'],
                    $_POST['title'],
                    $_POST['publicationyear'],
                    $_POST['genre'],
                    $availability,
                    $stock,
                    "public/uploads/" . $_FILES['file']['name']
                );

                $bookRepository = new BookRepository();
                $bookData = $bookRepository->insertBook($book);

                if ($bookData) {
                    $response['status'] = 'success';
                    $response['message'] = 'Książka została dodana pomyślnie.';
                    $response['book'] = $bookData;  // Dodajemy tutaj informacje o książce
                } else {
                    $response['status'] = 'error';
                    $response['message'] = $this->message ?: 'Błąd podczas dodawania książki.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = $this->message ?: 'Błąd podczas dodawania książki.';
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }


    public function editBook()
    {
        $response = [];

        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        if ($this->isPost()) {
            $bookId = $_POST['id'];
            $stock = $_POST['stock'];
            $availability = $stock > 0 ? true : false;

            // Jeśli plik został przesłany
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $imagePath = "public/uploads/" . $_FILES['file']['name'];

                if (!$this->validate($_FILES['file'])) {
                    $response['status'] = 'error';
                    $response['messages'] = $this->message;
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }

                move_uploaded_file($_FILES['file']['tmp_name'], dirname(__DIR__) . self::UPLOAD_DIRECTORY . $_FILES['file']['name']);
            } else {
                $imagePath = $_POST['hiddenFilePath'];
            }

            $book = new Book(
                $bookId,
                $_POST['author'],
                $_POST['title'],
                $_POST['publicationyear'],
                $_POST['genre'],
                $availability,
                $stock,
                $imagePath
            );

            $bookRepository = new BookRepository();
            $updatedBook = $bookRepository->updateBook($book);

            $response['book'] = $updatedBook;

            $response['status'] = 'success';
            $response['message'] = 'Książka została zaktualizowana pomyślnie.';
        } else {
            $response['status'] = 'error';
            $response['messages'] = $this->message ?: 'Nieprawidłowe żądanie!';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function removeBook()
    {
        $response = [];

        if (!$this->isAdmin()) {
            die("Brak uprawnień do wejścia na podaną stronę!");
        }

        if ($this->isPost()) {
            // Odczytanie surowych danych POST w formacie JSON
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON, true);
            $bookId = $input['id'];

            $bookRepository = new BookRepository();
            $result = $bookRepository->deleteBook($bookId);

            if ($result) { // Jeśli operacja usuwania się powiodła
                $response['status'] = 'success';
                $response['message'] = 'Książka została usunięta pomyślnie.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Wystąpił błąd podczas usuwania książki!';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Nieprawidłowe żądanie!';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

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
}

