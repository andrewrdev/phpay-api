<?php

declare(strict_types=1);

namespace src\classes\database;

use PDO;
use PDOException;

class DatabaseConnection
{  
    public static function getConnection()
    {        
        try {
            $properties = parse_ini_file(__DIR__ . "/../../application.properties");
    
            $host = $properties['DATABASE_HOST'];
            $dbname = $properties['DATABASE_NAME'];
            $username = $properties['DATABASE_USER'];
            $password = $properties['DATABASE_PASSWORD'];
    
            $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
            exit;
        }
    }
}
