<?php

declare(strict_types=1);

namespace src\repositories;

use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use src\app\http\Response;
use Exception;
use PDO;

class UserRepository implements Repository
{
    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM users";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectBalance(int $userId)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT balance FROM users WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($userId));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage()], 500);             
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();            
            $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($id));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectByEmail(string $email)
    {
        $conn = null;        
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($email));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectByCpfCnpj(string $cpf_cnpj)
    {
        $conn = null;        
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM users WHERE cpf_cnpj = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($cpf_cnpj));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************   

    public static function insert(object $user)
    {
        $conn = null; 
        try {
            $conn = DatabaseConnection::getConnection();            
            $conn->beginTransaction();
            $query = "INSERT INTO users (`full_name`,`cpf_cnpj`, `email`,`password`,`type`) 
                      VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $user->getFullName(),
                $user->getCpfCnpj(),                
                $user->getEmail(),
                $user->getPassword(),
                $user->getType()
            ));

            $conn->commit(); 
            return $conn; 
        } catch (Exception $e) {
            if($conn) {
                $conn->rollBack();
            }            
            Response::json(['message' => $e->getMessage()], 500);           
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $user)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            $query = "UPDATE users SET `full_name` = ?, `password` = ? WHERE id = ?";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $user->getFullName(),                
                $user->getPassword(),                
                $user->getId()
            ));

            $conn->commit(); 
            return $conn;
        } catch (Exception $e) {
            if($conn) {
                $conn->rollBack();
            }            
            Response::json(['message' => $e->getMessage()], 500);             
        } finally {            
            $conn = null;            
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($id));
            $conn->commit(); 
            return $conn;
        } catch (Exception $e) {
            if($conn) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function updateBalance(int $userId, float $amount)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            $query = "UPDATE users SET balance = balance + ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($amount, $userId));
            $conn->commit(); 
            return $conn;
        } catch (Exception $e) {
            if($conn) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500); 
        } finally {
            $conn = null;
        }
    }
    // ********************************************************************************************
    // ********************************************************************************************    
}