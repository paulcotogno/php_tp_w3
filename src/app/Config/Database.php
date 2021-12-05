<?php

namespace App\Config;

use PDOException;

class Database
{
    private static $host = "db";

    private static $database_name = "projet-cms";

    private static $username = "root";

    private static $password = "example";


    public static function getConnection(): \PDO
    {
        try {
            $conn = new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$database_name, self::$username, self::$password);
            $conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Database could not be connected: " . $exception->getMessage();
        }

        return $conn;
    }
}

?>
