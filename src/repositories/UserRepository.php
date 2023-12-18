<?php 

namespace src\repositories;

use Exception;
use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use PDO;
use src\models\UserModel;

class UserRepository implements Repository
{

public static function selectAll()
{
    try {
        $conn = DatabaseConnection::getConnection();
        $query = "SELECT * FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
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
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $user;
        } else {
            http_response_code(404);
            return ['message' => 'User not found'];
        }
    } catch (Exception $e) {
        http_response_code(500);
        return ['message' => $e->getMessage()];
    }
}
    
    public static function insert(object $user)
    {        
    }

    public static function update(object $user)
    {        
    }

    public static function deleteById(int $id)
    {      
        $conn = DatabaseConnection::getConnection();

        try {
            $conn->beginTransaction();
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($id));
            $conn->commit();
            
            http_response_code(200);
            return ['message' => 'User deleted successfully'];

        } catch (Exception $e) {
            $conn->rollBack();
            http_response_code(500);
            return ['message' => $e->getMessage()];
        }  
    } 
}