<?php

class ReservedBook
{
    private $id;
    private $userId;
    private $userName; // Dodane pole na imię i nazwisko użytkownika
    private $copyId;
    private $reservationDate;
    private $reservationEnd;
    private $title;
    private $author; // Dodane pole na autora książki

    public function __construct($id, $userId, $copyId, $reservationDate, $reservationEnd, $title, $author ,$userName = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->copyId = $copyId;
        $this->reservationDate = $reservationDate;
        $this->reservationEnd = $reservationEnd;
        $this->title = $title;
        $this->author = $author;
        $this->userName = $userName;     // Inicjalizacja nowego pola wartością domyślną null
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getCopyId()
    {
        return $this->copyId;
    }

    public function setCopyId($copyId): void
    {
        $this->copyId = $copyId;
    }

    public function getReservationDate()
    {
        return $this->reservationDate;
    }

    public function setReservationDate($reservationDate): void
    {
        $this->reservationDate = $reservationDate;
    }

    public function getReservationEnd()
    {
        return $this->reservationEnd;
    }

    public function setReservationEnd($reservationEnd): void
    {
        $this->reservationEnd = $reservationEnd;
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