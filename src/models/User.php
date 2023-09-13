<?php

class User
{
    private $email;
    private $password;
    private $name;
    private $lastname;
    private $role;


    public function __construct($email, $password, $name, $lastname, $role)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->role = $role;
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

    public function getRole(): string
    {
        return $this->role;
    }

}