<?php

class User
{
    private $email;
    private $password;
    private $name;
    private $lastname;


    public function __construct($email, $password, $name, $lastname)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}