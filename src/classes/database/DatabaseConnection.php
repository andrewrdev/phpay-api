<?php

declare(strict_types=1);

namespace src\classes\database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static $host;
    private static $dbname;
    private static $username;
    private static $password;
    private static $pdo;

    // ********************************************************************************************
    // ********************************************************************************************

    private static function connect()
    {
        $properties = parse_ini_file(__DIR__ . "/../../application.properties");

        self::$host = $properties['DATABASE_HOST'];
        self::$dbname = $properties['DATABASE_NAME'];
        self::$username = $properties['DATABASE_USER'];
        self::$password = $properties['DATABASE_PASSWORD'];

        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8";

        try {
            self::$pdo = new PDO($dsn, self::$username, self::$password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database Connection Failed!']);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getConnection()
    {
        self::connect();
        return self::$pdo;
    }

    // ********************************************************************************************
    // ********************************************************************************************
}
