<?php

namespace Source\Models;

use PDO;
use Source\Entities\UserEntity;

class UserModel extends PDO {

    private $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function update(UserEntity $userEntity)
    {
        $statement = $this->connection->prepare("UPDATE users SET name = :name, email = :email, password = :password, drink_counter = :drinkCounter WHERE id = :id");
        $statement->bindValue(":name", $userEntity->getName());
        $statement->bindValue(":email", $userEntity->getEmail());
        $statement->bindValue(":password", $userEntity->getPassword());
        $statement->bindValue(":drinkCounter", $userEntity->getDrinkCounter());
        $statement->bindValue(":id", $userEntity->getId());

        return $statement->execute();
    }

    public function delete(int $userId)
    {
        $statement = $this->connection->prepare("DELETE FROM users WHERE id = :id");
        $statement->bindValue(":id", $userId);

        return $statement->execute();
    }

    public function getTotalCount()
    {
        $statement = $this->connection->prepare("SELECT * FROM users");
        $statement->execute();

        return $statement->rowCount();
    }

    public function getById(Int $id)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(':id', $id);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserEntity(
                $user['name'],
                $user['email'],
                $user['password'],
                $user['drink_counter'],
                $user['id']
            );
        }

        return "";
    }

    public function getAll($page, $offset)
    {
        $query = "SELECT * FROM users";

        // Check if page is set
        if ($page) {

            // Set limit and offset
            $index = ($page - 1) * $offset;

            // Add in query
            $query .= " LIMIT $index, $offset";
        }

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        $userList = [];

        foreach($users as $user) {
            $userList[] = new UserEntity(
                $user['name'],
                $user['email'],
                $user['password'],
                $user['drink_counter'],
                $user['id']
            );
        }

        return $userList;
    }

    public function getByEmailPassword(string $email, string $password)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", sha1($password));
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserEntity(
                $user['name'],
                $user['email'],
                $user['password'],
                $user['drink_counter'],
                $user['id']
            );
        }

        return "";
    }

    public function getByEmail(string $email)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserEntity(
                $user['name'],
                $user['email'],
                $user['password'],
                $user['drink_counter'],
                $user['id']
            );
        }

        return "";
    }

    public function insert(UserEntity $userEntity)
    {
        $statement = $this->connection->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $statement->bindValue(":name", $userEntity->getName());
        $statement->bindValue(":email", $userEntity->getEmail());
        $statement->bindValue(":password", sha1($userEntity->getPassword()));
        
        $success = $statement->execute(); 
        
        if ($success) {
            $userEntity->setId($this->connection->lastInsertId());
        }

        return $success;
    }
}