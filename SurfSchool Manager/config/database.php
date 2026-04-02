<?php


class Database {

    private static ?PDO $connection = null;
    private function __construct() {}
    public static function getConnection(): PDO {

        if (self::$connection === null) {

            $host   = 'localhost';
            $dbname = 'surfschoolmanager';
            $user   = 'root';
            $pass   = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$connection = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}