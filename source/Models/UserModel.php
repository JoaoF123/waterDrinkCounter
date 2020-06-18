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
        $statement = $this->connection->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $statement->bindValue(":name", $userEntity->getName());
        $statement->bindValue(":email", $userEntity->getEmail());
        $statement->bindValue(":password", $userEntity->getPassword());
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
        $query = "SELECT users.id, users.email, users.name, users.password, ";
        $query .= "IF(SUM(user_drink.drink_ml) IS NULL, 0, SUM(user_drink.drink_ml)) AS drink_counter";
        $query .= " FROM users";
        $query .= " LEFT JOIN user_drink ON users.id = user_drink.id_user";
        $query .= " WHERE users.id = :id";
        $query .= " GROUP BY users.id";

        $statement = $this->connection->prepare($query);
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
        $query = "SELECT users.id, users.email, users.name, users.password, ";
        $query .= "IF(SUM(user_drink.drink_ml) IS NULL, 0, SUM(user_drink.drink_ml)) AS drink_counter";
        $query .= " FROM	users";
        $query .= " LEFT JOIN user_drink ON users.id = user_drink.id_user";
        $query .= " GROUP BY users.id";

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
        $query = "SELECT users.id, users.email, users.name, users.password, ";
        $query .= "IF(SUM(user_drink.drink_ml) IS NULL, 0, SUM(user_drink.drink_ml)) AS drink_counter";
        $query .= " FROM users";
        $query .= " LEFT JOIN user_drink ON users.id = user_drink.id_user";
        $query .= " WHERE users.email = :email AND users.password = :password";
        $query .= " GROUP BY users.id";

        $statement = $this->connection->prepare($query);
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
        $query = "SELECT users.id, users.email, users.name, users.password, ";
        $query .= "IF(SUM(user_drink.drink_ml) IS NULL, 0, SUM(user_drink.drink_ml)) AS drink_counter";
        $query .= " FROM users";
        $query .= " LEFT JOIN user_drink ON users.id = user_drink.id_user";
        $query .= " WHERE users.email = :email";
        $query .= " GROUP BY users.id";

        $statement = $this->connection->prepare($query);
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