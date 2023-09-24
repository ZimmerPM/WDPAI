<?php

class BorrowedBook
{

private $id;
    private $userId;
    private $userName; // Dodane pole na imię i nazwisko użytkownika
    private $copyId;
    private $borrowedDate;
    private $expectedReturnDate;
    private $actualReturnDate;
    private $title; // Dodane pole na tytuł książki
    private $author; // Dodane pole na autora książki

    public function __construct($id, $userId, $copyId, $borrowedDate, $expectedReturnDate, $title, $author, $actualReturnDate = null, $userName = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->copyId = $copyId;
        $this->borrowedDate = $borrowedDate;
        $this->expectedReturnDate = $expectedReturnDate;
        $this->actualReturnDate = $actualReturnDate;
        $this->title = $title;
        $this->author = $author;
        $this->userName = $userName; // Inicjalizacja nowego pola wartością domyślną null
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCopyId()
    {
        return $this->copyId;
    }

    public function getBorrowedDate()
    {
        return $this->borrowedDate;
    }

    public function getExpectedReturnDate()
    {
        return $this->expectedReturnDate;
    }

    public function getActualReturnDate()
    {
        return $this->actualReturnDate;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function setCopyId($copyId): void
    {
        $this->copyId = $copyId;
    }

    public function setBorrowedDate($borrowedDate): void
    {
        $this->borrowedDate = $borrowedDate;
    }

    public function setExpectedReturnDate($expectedReturnDate): void
    {
        $this->expectedReturnDate = $expectedReturnDate;
    }

    public function setActualReturnDate($actualReturnDate): void
    {
        $this->actualReturnDate = $actualReturnDate;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName): void
    {
        $this->userName = $userName;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author): void
    {
        $this->author = $author;
    }

}