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

    public function getRanking()
    {
        $query = "SELECT users.name, SUM(user_drink.drink_ml) AS drink_ml ";
        $query .= "FROM user_drink ";
        $query .= "INNER JOIN users ON user_drink.id_user = users.id ";
        $query .= "WHERE user_drink.created_at > '". date("Y-m-d 00:00:00") ."' AND user_drink.created_at < '" . date("Y-m-d 23:59:59") ."' ";
        $query .= "GROUP BY users.id ";
        $query .= "ORDER BY drink_ml DESC";

        $statement = $this->connection->prepare($query);

        return ($statement->execute()) ? $statement->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}