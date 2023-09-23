<?php

require_once __DIR__.'/../models/BorrowedBook.php';
require_once __DIR__.'/../models/ReservedBook.php';
require_once 'Repository.php';


class BorrowRepository extends Repository
{
    public function getTableName(): string
    {
        return 'borrowed_books';
    }


    public function getAvailableCopyId(int $bookId): ?int
    {
        $stmt = $this->database->connect()->prepare('
        SELECT id FROM book_copies
        WHERE book_id = :bookId AND status = \'available\'
        LIMIT 1
    ');
        $stmt->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return $result ? (int)$result['id'] : null;
    }

    // Tutaj możesz dodać więcej metod związanych z operacjami na wypożyczonych książkach.


    public function reserveBook(int $userId, int $copyId): void
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            $date = new DateTime();
            $reservationDate = $date->format('Y-m-d');
            $reservationEnd = $date->modify('+7 days')->format('Y-m-d');

            $stmt = $pdo->prepare('
            INSERT INTO reserved_books (user_id, copy_id, reservation_date, reservation_end)
            VALUES (:userId, :copyId, :reservationDate, :reservationEnd)
        ');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':copyId', $copyId, PDO::PARAM_INT);
            $stmt->bindParam(':reservationDate', $reservationDate, PDO::PARAM_STR);
            $stmt->bindParam(':reservationEnd', $reservationEnd, PDO::PARAM_STR);

            $stmt->execute();

            // Aktualizacja statusu kopii
            $stmt = $pdo->prepare('
            UPDATE book_copies SET status = \'reserved\' WHERE id = :copyId
        ');
            $stmt->bindParam(':copyId', $copyId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie transakcji
            $pdo->commit();

        } catch (\PDOException $e) {
            // Wycofanie transakcji w przypadku błędu
            $pdo->rollBack();
            throw new \Exception("Error reserving the book: " . $e->getMessage());
        }
    }


    public function getReservationsForUser(int $userId): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT reserved_books.*, books.title as title
        FROM reserved_books
        INNER JOIN book_copies ON reserved_books.copy_id = book_copies.id
        INNER JOIN books ON book_copies.book_id = books.id
        WHERE reserved_books.user_id = :userId
        ORDER BY reserved_books.reservation_date
    ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($reservations as $reservation) {
            $result[] = new ReservedBook(
                $reservation['id'],
                $reservation['user_id'],
                $reservation['copy_id'],
                $reservation['reservation_date'],
                $reservation['reservation_end'],
                $reservation['title'] // Dodajemy tytuł książki do obiektu ReservedBook
            );
        }

        return $result;
    }

    public function cancelBookReservation(int $reservationId): void
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            // Pobranie copy_id z tabeli reserved_books
            $stmt = $pdo->prepare('SELECT copy_id FROM reserved_books WHERE id = :reservationId');
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();
            $copyId = $stmt->fetchColumn();

            if (!$copyId) {
                throw new \Exception("Reservation not found");
            }

            // Usunięcie rezerwacji z tabeli reserved_books
            $stmt = $pdo->prepare('DELETE FROM reserved_books WHERE id = :reservationId');
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            // Aktualizacja statusu kopii
            $stmt = $pdo->prepare('UPDATE book_copies SET status = \'available\' WHERE id = :copyId');
            $stmt->bindParam(':copyId', $copyId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie transakcji
            $pdo->commit();
        } catch (\PDOException $e) {
            // Wycofanie transakcji w przypadku błędu
            $pdo->rollBack();
            throw new \Exception("Error cancelling the reservation: " . $e->getMessage());
        }
    }






















    public function getBorrowedBooksByUserId(int $userId): array
    {
        $stmt = $this->database->connect()->prepare(
            'SELECT * FROM borrowed_books WHERE user_id = :userId'
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $borrowedBooks = [];
        foreach ($stmt as $row) {
            $borrowedBook = new BorrowedBook(
                $row['id'],
                $row['user_id'],
                $row['copy_id'],
                $row['borrowed_date'],
                $row['expected_return_date'],
                $row['actual_return_date']
            );
            $borrowedBooks[] = $borrowedBook;
        }

        return $borrowedBooks;
    }

    public function addBorrowedBook(int $userId, int $copyId, string $borrowedDate, string $expectedReturnDate)
    {
        $stmt = $this->database->connect()->prepare(
            'INSERT INTO borrowed_books (user_id, copy_id, borrowed_date, expected_return_date) VALUES (:userId, :copyId, :borrowedDate, :expectedReturnDate)'
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':copyId', $copyId, PDO::PARAM_INT);
        $stmt->bindParam(':borrowedDate', $borrowedDate, PDO::PARAM_STR);
        $stmt->bindParam(':expectedReturnDate', $expectedReturnDate, PDO::PARAM_STR);

        $stmt->execute();
    }
}




