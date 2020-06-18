<?php

namespace Source\Services;

use PDO;

class ConnectionCreator {
    
    private static $pdo;

    public static function create() : PDO
    {
        if (!isset(self::$pdo)) {
            self::$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        }

        return self::$pdo;
    }
}