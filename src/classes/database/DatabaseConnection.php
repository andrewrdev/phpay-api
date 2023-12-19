<?php

declare(strict_types=1);

namespace src\classes\database;

use PDO;
use PDOException;
use src\classes\util\ApplicationProperties;

class DatabaseConnection
{  
    // ********************************************************************************************
    // ********************************************************************************************
    public static function getConnection()
    {        
        try {          
    
            $host = ApplicationProperties::get('DATABASE_HOST');
            $dbname = ApplicationProperties::get('DATABASE_NAME');
            $username = ApplicationProperties::get('DATABASE_USER');
            $password = ApplicationProperties::get('DATABASE_PASSWORD');
    
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
    
    // ********************************************************************************************
    // ********************************************************************************************
}
