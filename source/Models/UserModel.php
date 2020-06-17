<?php

namespace Source\Models;

use PDO;
use Source\Entities\UserEntity;

class UserModel extends PDO {

    private $pdo;

    public function __construct()
    {
        parent::__construct(DB_DSN, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
    }

    public function getById(Int $id)
    {
        $statement = parent::prepare("SELECT * FROM users WHERE id = :id");
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

    public function getAll()
    {
        $statement = parent::prepare("SELECT * FROM users");
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
        $statement = parent::prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
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
}