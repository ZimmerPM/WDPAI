<?php

class ReserveRepository
{

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
}