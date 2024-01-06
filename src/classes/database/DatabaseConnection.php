<?php

declare(strict_types=1);

namespace src\classes\database;

use PDO;
use PDOException;
use src\app\http\Response;
use src\classes\util\ApplicationProperties;

class DatabaseConnection
{

    /**************************************************************************
     * Retrieves a database connection.
     *
     * @throws PDOException If there is an error connecting to the database.
     * @return PDO The database connection.
     *************************************************************************/

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
            Response::json(["error" => $e->getMessage()], 500);
        }
    }
}
