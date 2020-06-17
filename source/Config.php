<?php

    define("BASE_URL", "http://localhost/restfulapi");

    define("DB_DSN", "mysql:host=127.0.0.1; dbname=waterdrinkcounter; charset=utf8");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DB_OPTIONS", [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);