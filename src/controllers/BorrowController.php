<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/BorrowedBookRepository.php';
require_once __DIR__.'/../repository/BookRepository.php';

class BorrowController extends AppController
{
    private BorrowedBookRepository $borrowedBookRepository;
    private BookRepository $bookRepository;

    public function __construct()
    {
        parent::__construct();
        $this->borrowedBookRepository = new BorrowedBookRepository();
        $this->bookRepository = new BookRepository();
    }

    public function borrow(int $bookId)
    {
        if (!$this->isLoggedIn()) {
            $this->render('login', ['messages' => ['Please log in to borrow a book']]);
            return;
        }

        $bookId = $_POST['bookId'];

        $userId = $_SESSION['id'];
        $borrowedDate = date('Y-m-d');
        $expectedReturnDate = date('Y-m-d', strtotime('+30 days'));

        try {
            $this->borrowedBookRepository->addBorrowedBook($userId, $bookId, $borrowedDate, $expectedReturnDate);
            $this->bookRepository->setBookStatus($bookId, 'borrowed');
            $this->render('borrow-success', ['message' => 'Book borrowed successfully']);
        } catch (Exception $e) {
            $this->render('error', ['message' => 'Error borrowing book: ' . $e->getMessage()]);
        }
    }

    // Możesz dodać więcej metod do obsługi innych operacji związanych z wypożyczaniem, np. zwrot książek.
}