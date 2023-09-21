<?php

require_once 'Repository.php';
require_once __DIR__ . '/../models/Book.php';

class BookRepository extends Repository
{
    public function getBooks(): array
    {
        $stmt = $this->database->connect()->prepare('
             SELECT books.*, authors.name as author
        FROM books
        INNER JOIN books_authors ON books.id = books_authors.book_id
        INNER JOIN authors ON authors.id = books_authors.author_id
        ORDER BY authors.name ASC;
        ');

        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($books as $book) {
            $result[] = new Book(
                $book['id'],
                $book['author'],
                $book['title'],
                $book['publicationyear'],
                $book['genre'],
                (bool)$book['availability'],
                $book['stock'],
                $book['image']
            );

        }

        return $result;
    }

    public function searchBooks(string $query): array
    {
        $searchQuery = '%' . strtolower($query) . '%';

        $stmt = $this->database->connect()->prepare('
        SELECT books.*, authors.name as author
        FROM books
        INNER JOIN books_authors ON books.id = books_authors.book_id
        INNER JOIN authors ON authors.id = books_authors.author_id
        WHERE LOWER(books.title) LIKE :query OR LOWER(authors.name) LIKE :query OR LOWER(books.genre) LIKE :query
        ORDER BY authors.name ASC
    ');

        $stmt->bindParam(':query', $searchQuery, PDO::PARAM_STR);
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($books as $book) {
            $result[] = new Book(
                $book['id'],
                $book['author'],
                $book['title'],
                $book['publicationyear'],
                $book['genre'],
                (bool)$book['availability'],
                $book['stock'],
                $book['image']
            );

        }

        return $result;
    }

    public function insertBook(Book $book)
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            $authorName = $book->getAuthor();
            $title = $book->getTitle();
            $publicationYear = $book->getPublicationYear();
            $genre = $book->getGenre();
            $availability = $book->isAvailable();
            $stock = $book->getStock();
            $image = $book->getImage();

            // 1. Sprawdź, czy autor istnieje
            $stmt = $pdo->prepare('SELECT id FROM authors WHERE name = :author');
            $stmt->bindParam(':author', $authorName, PDO::PARAM_STR);
            $stmt->execute();

            $author = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Jeśli nie istnieje, dodaj go
            if (!$author) {
                $stmt = $pdo->prepare('INSERT INTO authors (name) VALUES (:author)');
                $stmt->bindParam(':author', $authorName, PDO::PARAM_STR);
                $stmt->execute();

                $authorId = $pdo->lastInsertId();
            } else {
                $authorId = $author['id'];
            }

            // 3. Dodaj książkę
            $stmt = $pdo->prepare('
        INSERT INTO books (title, publicationyear, genre, availability, stock, image)
        VALUES (:title, :publicationyear, :genre, :availability, :stock, :image)
        ');

            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':publicationyear', $publicationYear, PDO::PARAM_INT);
            $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
            $stmt->bindParam(':availability', $availability, PDO::PARAM_BOOL);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);

            $stmt->execute();

            $bookId = $pdo->lastInsertId();

            // 4. Dodaj relację w tabeli books_authors
            $stmt = $pdo->prepare('INSERT INTO books_authors (book_id, author_id) VALUES (:book_id, :author_id)');
            $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
            $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie zmian w transakcji
            $pdo->commit();

            return [
                'id' => $bookId,
                'title' => $title,
                'author' => $authorName,
                'publicationyear' => $publicationYear,
                'genre' => $genre,
                'availability' => $availability,
                'stock' => $stock,
                'image' => $image
            ];

        } catch (\Exception $e) {
            // Wycofanie zmian w transakcji w przypadku błędu
            $pdo->rollBack();
            throw $e;  // Rzucenie wyjątku dalej, aby móc go obsłużyć w kodzie wywołującym
        }

        return null;
    }

    public function updateBook(Book $book)
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            $authorName = $book->getAuthor();

            // 1. Sprawdź, czy autor istnieje
            $stmt = $pdo->prepare('SELECT id FROM authors WHERE name = :author');
            $stmt->bindParam(':author', $authorName, PDO::PARAM_STR);
            $stmt->execute();

            $author = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Jeśli nie istnieje, dodaj go
            if (!$author) {
                $stmt = $pdo->prepare('INSERT INTO authors (name) VALUES (:author)');
                $stmt->bindParam(':author', $authorName, PDO::PARAM_STR);
                $stmt->execute();

                $authorId = $pdo->lastInsertId();
            } else {
                $authorId = $author['id'];
            }

            // 3. Aktualizuj książkę
            $stmt = $pdo->prepare('
            UPDATE books
            SET title = :title, publicationyear = :publicationyear, genre = :genre, stock = :stock, image = :image
            WHERE id = :id
        ');

            $stmt->bindParam(':title', $book->getTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':publicationyear', $book->getPublicationYear(), PDO::PARAM_INT);
            $stmt->bindParam(':genre', $book->getGenre(), PDO::PARAM_STR);
            $stmt->bindParam(':stock', $book->getStock(), PDO::PARAM_INT);
            $stmt->bindParam(':image', $book->getImage(), PDO::PARAM_STR);
            $stmt->bindParam(':id', $book->getId(), PDO::PARAM_INT);
            $stmt->execute();

            // 4. Aktualizuj relację w tabeli books_authors
            $stmt = $pdo->prepare('UPDATE books_authors SET author_id = :author_id WHERE book_id = :book_id');
            $stmt->bindParam(':book_id', $book->getId(), PDO::PARAM_INT);
            $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie zmian w transakcji
            $pdo->commit();

            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'publicationyear' => $book->getPublicationYear(),
                'genre' => $book->getGenre(),
                'stock' => $book->getStock(),
                'image' => $book->getImage()
            ];

        } catch (\Exception $e) {
            // Wycofanie zmian w transakcji w przypadku błędu
            $pdo->rollBack();
            throw $e;
        }
    }

    public function deleteBook(int $bookId): bool
    {
        $pdo = $this->database->connect();

        try {
            // Rozpoczęcie transakcji
            $pdo->beginTransaction();

            // 1. Usuwanie relacji z tabeli books_authors
            $stmt = $pdo->prepare('DELETE FROM books_authors WHERE book_id = :id');
            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
            $stmt->execute();

            // 2. Usuwanie książki z tabeli books
            $stmt = $pdo->prepare('DELETE FROM books WHERE id = :id');
            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
            $stmt->execute();

            // Zatwierdzenie zmian w transakcji
            $pdo->commit();

            // Zwracanie prawdy, jeśli przynajmniej jedna książka została usunięta
            return $stmt->rowCount() > 0;

        } catch (\Exception $e) {
            // Wycofanie zmian w transakcji w przypadku błędu
            $pdo->rollBack();
            throw $e;  // Rzucenie wyjątku dalej, aby móc go obsłużyć w kodzie wywołującym
        }
    }

}
?>