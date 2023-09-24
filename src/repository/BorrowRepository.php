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


    public function reserveBook(int $userId, int $copyId): void
    {
        $pdo = $this->database->connect();

        try {
            $pdo->beginTransaction();

            $date = new DateTime();
            $reservationDate = $date->format('Y-m-d');
            $reservationEnd = $date->modify('+7 days')->format('Y-m-d');

            $this->addRecordToTable('reserved_books', [
                'user_id' => $userId,
                'copy_id' => $copyId,
                'reservation_date' => $reservationDate,
                'reservation_end' => $reservationEnd,
            ]);

            $this->setCopyStatus($copyId, 'reserved');

            $pdo->commit();

        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \Exception("Error reserving the book: " . $e->getMessage());
        }
    }


    public function getReservationsForUser(int $userId): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT reserved_books.*, books.title as title, 
               STRING_AGG(authors.name, \', \') as authors
        FROM reserved_books
        INNER JOIN book_copies ON reserved_books.copy_id = book_copies.id
        INNER JOIN books ON book_copies.book_id = books.id
        LEFT JOIN books_authors ON books.id = books_authors.book_id
        LEFT JOIN authors ON books_authors.author_id = authors.id
        WHERE reserved_books.user_id = :userId
        GROUP BY reserved_books.id, books.title
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
                $reservation['title'],
                $reservation['authors'],
                null
            );
        }

        return $result;
    }


    public function cancelBookReservation(int $reservationId): void
    {
        $pdo = $this->database->connect();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('SELECT copy_id FROM reserved_books WHERE id = :reservationId');
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();
            $copyId = $stmt->fetchColumn();

            if (!$copyId) {
                throw new \Exception("Reservation not found");
            }

            $this->deleteRecordFromTable('reserved_books', $reservationId);
            $this->setCopyStatus($copyId, 'available');

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \Exception("Error cancelling the reservation: " . $e->getMessage());
        }
    }


    public function getAllReservations(): array
    {
        $stmt = $this->database->connect()->prepare("
        SELECT reserved_books.*, books.title as title, 
        STRING_AGG(authors.name, ', ') as authors, 
        CONCAT(user_details.name, ' ', user_details.lastname) as user_name,
        users.id as user_id
        FROM reserved_books
        INNER JOIN book_copies ON reserved_books.copy_id = book_copies.id
        INNER JOIN books ON book_copies.book_id = books.id
        INNER JOIN users ON reserved_books.user_id = users.id
        INNER JOIN user_details ON users.id = user_details.user_id
        LEFT JOIN books_authors ON books.id = books_authors.book_id
        LEFT JOIN authors ON books_authors.author_id = authors.id
        GROUP BY reserved_books.id, books.title, user_name, users.id
        ORDER BY users.id, reserved_books.reservation_date
    ");
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
                $reservation['title'],
                $reservation['authors'],
                $reservation['user_name']
            );
        }

        return $result;
    }


    public function addBorrowedBook(int $userId, int $copyId, string $borrowedDate, string $expectedReturnDate): void
    {
        $this->addRecordToTable('borrowed_books', [
            'user_id' => $userId,
            'copy_id' => $copyId,
            'borrowed_date' => $borrowedDate,
            'expected_return_date' => $expectedReturnDate,
        ]);
    }


    public function executeBookLending(int $reservationId): void
    {
        {
            $pdo = $this->database->connect();

            try {
                $pdo->beginTransaction();

                // Pobranie informacji o rezerwacji
                $stmt = $pdo->prepare('SELECT user_id, copy_id FROM reserved_books WHERE id = :reservationId');
                $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
                $stmt->execute();

                $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reservation) {
                    throw new \Exception("Reservation not found");
                }

                $userId = $reservation['user_id'];
                $copyId = $reservation['copy_id'];

                // Usunięcie rezerwacji
                $this->deleteRecordFromTable('reserved_books', $reservationId);

                // Dodanie rekordu do tabeli borrowed_books
                $date = new DateTime();
                $borrowedDate = $date->format('Y-m-d');
                $expectedReturnDate = $date->modify('+30 days')->format('Y-m-d');

                $this->addBorrowedBook($userId, $copyId, $borrowedDate, $expectedReturnDate);

                // Zmiana statusu kopii książki na 'borrowed'
                $this->setCopyStatus($copyId, 'borrowed');

                $pdo->commit();
            } catch (\PDOException $e) {
                $pdo->rollBack();
                throw new \Exception("Error lending the book: " . $e->getMessage());
            }
        }
    }



    private function addRecordToTable(string $tableName, array $data): void
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $stmt = $this->database->connect()->prepare("
            INSERT INTO $tableName ($columns)
            VALUES ($values)
        ");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
    }


    private function setCopyStatus(int $copyId, string $status): void
    {
        $stmt = $this->database->connect()->prepare('
            UPDATE book_copies SET status = :status WHERE id = :copyId
        ');
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':copyId', $copyId, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function deleteRecordFromTable(string $tableName, int $id): void
    {
        $stmt = $this->database->connect()->prepare("
            DELETE FROM $tableName WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }



    public function getLoansForUser(int $userId): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT borrowed_books.*, books.title as title, 
               STRING_AGG(authors.name, \', \') as authors
        FROM borrowed_books
        INNER JOIN book_copies ON borrowed_books.copy_id = book_copies.id
        INNER JOIN books ON book_copies.book_id = books.id
        LEFT JOIN books_authors ON books.id = books_authors.book_id
        LEFT JOIN authors ON books_authors.author_id = authors.id
        WHERE borrowed_books.user_id = :userId
        GROUP BY borrowed_books.id, books.title
        ORDER BY borrowed_books.borrowed_date
    ');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($loans as $loan) {
            $result[] = new BorrowedBook(
                $loan['id'],
                $loan['user_id'],
                $loan['copy_id'],
                $loan['borrowed_date'],
                $loan['expected_return_date'],
                $loan['title'],
                $loan['authors'],
                $loan['actual_return_date']
            );
        }

        return $result;
    }


    public function getAllLoans(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT borrowed_books.*, books.title as title, 
               STRING_AGG(authors.name, \', \') as authors,
               CONCAT(user_details.name, \' \', user_details.lastname) as user_name
        FROM borrowed_books
        INNER JOIN book_copies ON borrowed_books.copy_id = book_copies.id
        INNER JOIN books ON book_copies.book_id = books.id
        INNER JOIN users ON borrowed_books.user_id = users.id
        INNER JOIN user_details ON users.id = user_details.user_id
        LEFT JOIN books_authors ON books.id = books_authors.book_id
        LEFT JOIN authors ON books_authors.author_id = authors.id
        GROUP BY borrowed_books.id, books.title, user_name
        ORDER BY borrowed_books.borrowed_date
    ');
        $stmt->execute();

        $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($loans as $loan) {
            $result[] = new BorrowedBook(
                $loan['id'],
                $loan['user_id'],
                $loan['copy_id'],
                $loan['borrowed_date'],
                $loan['expected_return_date'],
                $loan['title'],
                $loan['authors'],
                $loan['actual_return_date'],
                $loan['user_name']
            );
        }

        return $result;
    }

    public function cancelBorrowedBook(int $loanId): void {
        $pdo = $this->database->connect();

        try {
            $pdo->beginTransaction();

            // Pobranie user_id dla danego wypożyczenia
            $stmt = $pdo->prepare('SELECT user_id FROM borrowed_books WHERE id = :loanId');
            $stmt->bindParam(':loanId', $loanId, PDO::PARAM_INT);
            $stmt->execute();
            $userId = $stmt->fetchColumn();

            if (!$userId) {
                throw new \Exception("User not found for the given loan");
            }

            // Pobranie copy_id dla danego wypożyczenia
            $stmt = $pdo->prepare('SELECT copy_id FROM borrowed_books WHERE id = :loanId');
            $stmt->bindParam(':loanId', $loanId, PDO::PARAM_INT);
            $stmt->execute();
            $copyId = $stmt->fetchColumn();

            if (!$copyId) {
                throw new \Exception("Loan not found");
            }

            // Usunięcie rekordu z borrowed_books
            $this->deleteRecordFromTable('borrowed_books', $loanId);

            // Dodanie rekordu do reserved_books
            $date = new DateTime();
            $reservationDate = $date->format('Y-m-d');
            $reservationEnd = $date->modify('+7 days')->format('Y-m-d');

            $this->addRecordToTable('reserved_books', [
                'user_id' => $userId,
                'copy_id' => $copyId,
                'reservation_date' => $reservationDate,
                'reservation_end' => $reservationEnd,
            ]);

            // Zmiana statusu kopii książki na 'reserved'
            $this->setCopyStatus($copyId, 'reserved');

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw new \Exception("Error cancelling the loan: " . $e->getMessage());
        }
    }


}