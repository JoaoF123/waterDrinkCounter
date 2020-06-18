<?php

namespace Source\Models;

use PDO;
use Source\Services\ConnectionCreator;

class UserDrinkModel {
    
    private $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function save(int $idUser, int $drinkMl)
    {
        $statement = $this->connection->prepare("INSERT INTO user_drink (id_user, drink_ml) VALUES (:idUser, :drinkMl)");
        $statement->bindValue(":idUser", $idUser);
        $statement->bindValue(":drinkMl", $drinkMl);

        return $statement->execute();
    }

    public function deleteByUserId(int $userId)
    {
        $statement = $this->connection->prepare("DELETE FROM user_drink WHERE id_user = :userId");
        $statement->bindValue(":userId", $userId);

        return $statement->execute();
    }
}