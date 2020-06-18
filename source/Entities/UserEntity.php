<?php

namespace Source\Entities;

use Source\Services\Validation;

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

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = sha1($password);
    }

    public function setDrinkCounter(int $drinkCounter)
    {
        $this->drinkCounter = $drinkCounter;
    }

    public function validate()
    {
        // Define return array
        $errors = [];

        // Validate e-mail field
        if (!Validation::requiredString($this->email)) {
            array_push($errors, "E-mail is required.");
        } else if (!Validation::validEmail($this->email)) {
            array_push($errors, "E-mail is not valid.");
        }

        // Validate name field
        if (!Validation::requiredString($this->name)) {
            array_push($errors, "Name is required.");
        }

        // Validate password field
        if (!Validation::requiredString($this->password)) {
            array_push($errors, "Password is required.");
        }

        // Return errors found
        return $errors;
    }
}