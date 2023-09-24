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

    public function loans()
    {
        if ($this->isLoggedIn()) {
            $borrowRepository = new BorrowRepository();

            // Sprawdzenie, czy zalogowany użytkownik jest administratorem
            if ($this->isAdmin()) {
                // Pobranie wszystkich wypożyczeń dla administratora
                $loans = $borrowRepository->getAllLoans();
                $archiveLoans = $borrowRepository->getAllArchiveLoans(); // Pobranie archiwalnych wypożyczeń
            } else {
                // Pobranie wypożyczeń tylko dla zalogowanego użytkownika
                $userId = $_SESSION['user']['id'];
                $loans = $borrowRepository->getLoansForUser($userId);
                $archiveLoans = $borrowRepository->getArchiveLoansForUser($userId); // Pobranie archiwalnych wypożyczeń dla użytkownika
            }

            // Renderowanie widoku z przekazaniem listy wypożyczeń oraz archiwalnych wypożyczeń
            return $this->render('loans', ['loans' => $loans, 'archivedLoans' => $archiveLoans]);
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


    public function adminCancelReservation() {
        // Sprawdzenie, czy zalogowany użytkownik jest administratorem
        if (!$this->isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Brak uprawnień do anulowania rezerwacji']);
            return;
        }

        // Sprawdzenie, czy metoda jest POST
        if ($this->isPost()) {
            // Pobranie danych z żądania
            $data = json_decode(file_get_contents('php://input'), true);
            $reservationId = $data['reservationId'];

            // Utworzenie repozytorium
            $borrowRepository = new BorrowRepository();

            try {
                // Anulowanie rezerwacji książki
                $borrowRepository->cancelBookReservation($reservationId);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                // Obsługa błędu
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function lendBook() {
        // Sprawdzenie, czy użytkownik jest administratorem
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden: Only administrators can lend books']);
            exit;
        }

        // Sprawdzenie metody żądania
        if (!$this->isPost()) {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            exit;
        }

        // Pobranie danych z ciała żądania
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        // Sprawdzenie, czy reservationId jest ustawione
        if (!isset($input['reservationId'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bad Request: reservationId is required']);
            exit;
        }

        $reservationId = (int)$input['reservationId'];

        // Wywołanie metody repozytorium do wypożyczenia książki
        try {
            $this->borrowRepository->executeBookLending($reservationId);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Book has been lent successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Internal Server Error: ' . $e->getMessage()]);
        }
    }

    public function cancelLoan() {
        header('Content-Type: application/json');

        // Sprawdzenie, czy zalogowany użytkownik jest administratorem
        if (!$this->isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Brak uprawnień do anulowania wypożyczenia']);
            return;
        }

        // Sprawdzenie, czy metoda jest POST
        if ($this->isPost()) {
            // Pobranie danych z żądania
            $data = json_decode(file_get_contents('php://input'), true);
            $loanId = $data['loanId'];

            // Walidacja loanId
            if (empty($loanId) || !is_numeric($loanId)) {
                echo json_encode(['success' => false, 'message' => 'Invalid loanId']);
                return;
            }

            // Utworzenie repozytorium
            $borrowRepository = new BorrowRepository();

            try {
                // Anulowanie wypożyczenia książki
                $borrowRepository->cancelBorrowedBook((int)$loanId);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                // Obsługa błędu
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function returnBook() {
        // Sprawdzenie, czy użytkownik jest administratorem
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden: Only administrators can return books']);
            exit;
        }

        // Sprawdzenie metody żądania
        if (!$this->isPost()) {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            exit;
        }

        // Pobranie danych z ciała żądania
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        // Sprawdzenie, czy loanId jest ustawione
        if (!isset($input['loanId'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bad Request: loanId is required']);
            exit;
        }

        $loanId = (int)$input['loanId'];

        // Wywołanie metody repozytorium do zwrotu książki
        try {
            $this->borrowRepository->executeBookReturn($loanId);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Book has been returned successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Internal Server Error: ' . $e->getMessage()]);
        }
    }

}


