<?php

class User
{
    private $id;
    private $email;
    private $password;
    private $name;
    private $lastname;

    private $role;


    public function __construct($id, $email, $password, $name, $lastname,  $role = "user")
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->role = $role;
    }


    public function getId()
    {
        return $this->id;
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

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
}