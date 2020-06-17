<?php

namespace Source\Entities;

class UserEntity {

    private $id;
    private $name;
    private $email;
    private $password;
    private $drinkCounter;

    public function __construct(string $name, string $email, string $password, int $drinkCounter = 0, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->drinkCounter = $drinkCounter;
    }

    public function toArray()
    {
        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "email"         => $this->email,
            "password"      => $this->password,
            "drink_counter" => $this->drinkCounter
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDrinkCounter()
    {
        return $this->drinkCounter;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setDrinkCounter($drinCounter)
    {
        $this->drinkCounter = $drinkCounter;
    }
}