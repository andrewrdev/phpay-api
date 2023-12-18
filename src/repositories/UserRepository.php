<?php 

namespace src\repositories;

use Exception;
use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use PDO;

class UserRepository implements Repository
{

public static function selectAll(): array
{
    try {
        $conn = DatabaseConnection::getConnection();
        $query = "SELECT * FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            http_response_code(404);
            return ['message' => 'Users not found'];
        }
    } catch (Exception $e) {
        http_response_code(500);
        return ['message' => $e->getMessage()];
    }
}
    
public static function selectById(int $id)
{
    try {
        $conn = DatabaseConnection::getConnection();
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($id));

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            http_response_code(404);
            return ['message' => 'User not found'];
        }
    } catch (Exception $e) {
        http_response_code(500);
        return ['message' => $e->getMessage()];
    }
}
    
    public static function insert(object $model)
    {        
    }

    public static function update(object $model)
    {        
    }

    public static function deleteById(int $id)
    {        
    } 
}