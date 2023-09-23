<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/BorrowRepository.php';
require_once __DIR__.'/../repository/BookRepository.php';

class BorrowController extends AppController
{
    private BorrowRepository $borrowRepository;
    private BookRepository $bookRepository;

    public function __construct()
    {
        parent::__construct();
        $this->borrowRepository = new BorrowRepository();
        $this->bookRepository = new BookRepository();
    }


    public function loans()
    {
        if ($this->isLoggedIn())
        {
            return $this->render('loans');
        }
    }

    public function reservations()
    {
        if ($this->isLoggedIn()) {
            $borrowRepository = new BorrowRepository();

            // Sprawdzenie, czy zalogowany użytkownik jest administratorem
            if ($this->isAdmin()) {
                // Pobranie wszystkich rezerwacji dla administratora
                $reservations = $borrowRepository->getAllReservations();
            } else {
                // Pobranie rezerwacji tylko dla zalogowanego użytkownika
                $userId = $_SESSION['user']['id'];
                $reservations = $borrowRepository->getReservationsForUser($userId);
            }

            // Renderowanie widoku z przekazaniem listy rezerwacji
            return $this->render('reservations', ['reservations' => $reservations]);
        }
    }


    public function reserve()
    {

        if (!$this->isLoggedIn()) {
            return;
        }

        if ($this->isPost()) {
            $data = json_decode(file_get_contents('php://input'), true);
            $bookId = $data['bookId'];
            $userId = $_SESSION['user']['id'];

            $borrowRepository = new BorrowRepository();
            $copyId = $borrowRepository->getAvailableCopyId($bookId);

            if ($copyId) {
                $borrowRepository->reserveBook($userId, $copyId);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No available copies']);
            }
        }
    }

    public function cancelReservation()
    {
        if (!$this->isLoggedIn()) {
            return;
        }

        if ($this->isPost()) {
            $data = json_decode(file_get_contents('php://input'), true);
            $reservationId = $data['reservationId'];

            $borrowRepository = new BorrowRepository();

            try {
                $borrowRepository->cancelBookReservation($reservationId);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }










    public function borrow()
    {
        if (!$this->isLoggedIn()) {
            return;
        }

        if (!isset($_POST['bookId'])) {
            $this->render('borrow-error', ['message' => 'Niepoprawne ID książki']);
            return;
        }

        $bookId = $_POST['bookId'];
        $copyId = $this->borrowRepository->getAvailableCopyId($bookId);

        if (!$copyId) {
            $this->render('borrow-error', ['message' => 'Brak dostępnych egzemplarzy tej książki.']);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $borrowedDate = date('Y-m-d');
        $expectedReturnDate = date('Y-m-d', strtotime('+30 days'));

        try {
            $this->borrowRepository->addBorrowedBook($userId, $copyId, $borrowedDate, $expectedReturnDate);
            $this->bookRepository->setCopyStatus($copyId, 'borrowed');
            $this->render('borrow-success', ['message' => 'Książka pomyślnie wypożyczona']);
        } catch (Exception $e) {
            $this->render('borrow-error', ['message' => 'Błąd wypożyczenia książki: ' . $e->getMessage()]);
        }
    }
}


