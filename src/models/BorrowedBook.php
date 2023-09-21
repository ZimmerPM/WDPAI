<?php


class BorrowedBook
{
    private $id;
    private $userId;
    private $copyId;
    private $borrowedDate;
    private $expectedReturnDate;
    private $actualReturnDate;

    public function __construct($id, $userId, $copyId, $borrowedDate, $expectedReturnDate, $actualReturnDate = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->copyId = $copyId;
        $this->borrowedDate = $borrowedDate;
        $this->expectedReturnDate = $expectedReturnDate;
        $this->actualReturnDate = $actualReturnDate; // Inicjalizacja nowego pola
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



}