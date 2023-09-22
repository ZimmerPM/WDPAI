<?php

require_once __DIR__.'/../models/BorrowedBook.php';
require_once 'Repository.php';

class BorrowRepository extends Repository
{
    public function getTableName(): string
    {
        return 'borrowed_books';
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

        var_dump($result); // Debugowanie

        return $result ? (int) $result['id'] : null;
    }

    // Tutaj możesz dodać więcej metod związanych z operacjami na wypożyczonych książkach.
}