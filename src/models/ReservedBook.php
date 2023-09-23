<?php

class ReservedBook
{
    private $id;
    private $userId;
    private $copyId;
    private $reservationDate;
    private $reservationEnd;
    private $title;

    public function __construct($id, $userId, $copyId, $reservationDate, $reservationEnd, $title)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->copyId = $copyId;
        $this->reservationDate = $reservationDate;
        $this->reservationEnd = $reservationEnd;
        $this->title = $title;
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


}