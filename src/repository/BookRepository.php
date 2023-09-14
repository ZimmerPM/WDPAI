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
        INNER JOIN booksauthors ON books.id = booksauthors.book_id
        INNER JOIN authors ON authors.id = booksauthors.author_id
        ');

        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($books as $book) {
            $result[] = new Book(
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
        INNER JOIN booksauthors ON books.id = booksauthors.book_id
        INNER JOIN authors ON authors.id = booksauthors.author_id
        WHERE LOWER(books.title) LIKE :query OR LOWER(authors.name) LIKE :query
    ');

        $stmt->bindParam(':query', $searchQuery, PDO::PARAM_STR);
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($books as $book) {
            $result[] = new Book(
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


}
?>