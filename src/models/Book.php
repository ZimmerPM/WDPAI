<?php

class Book
{
    private $author;
    private $title;
    private $publicationYear;
    private $genre;
    private $availability;
    private $stock;

    private $image;

    public function __construct($author, $title, $publicationYear, $genre, $availability, $stock, $image)
    {
        $this->author = $author;
        $this->title = $title;
        $this->publicationYear = $publicationYear;
        $this->genre = $genre;
        $this->availability = $availability;
        $this->stock = $stock;
        $this->image = $image;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }


    public function getStock()
    {
        return $this->stock;
    }


    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getPublicationYear()
    {
        return $this->publicationYear;
    }

    public function setPublicationYear($publicationYear)
    {
        $this->publicationYear = $publicationYear;
    }


}