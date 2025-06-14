<?php

namespace App\Core;

use PDO;
use PDOException;


class Database
{
    private static $db;
    private function __construct()
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset={$_ENV['DB_CHARSET']}";
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        self::$db = new PDO($dsn, $user, $password);
    }


    public static function getInstance()
    {
        try {
            if (empty(self::$db)) {
                new Database();
            }
            return self::$db;
        } catch (PDOException $e) {
            throw new \RuntimeException('Fehler beim Verbinden zur Datenbank', 0, $e);
        }
    }
}
