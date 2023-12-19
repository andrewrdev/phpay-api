<?php

declare(strict_types=1);

namespace src\repositories;

use Exception;
use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
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
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
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
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
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
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectByCpf(string $cpf)
    {
        $conn = null;        
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM users WHERE cpf = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($cpf));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectByCnpj(string $cnpj)
    {
        $conn = null;        
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM users WHERE cnpj = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($cnpj));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
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
            $query = "INSERT INTO users (`full_name`,`cpf`,`cnpj`,`email`,`password`,`type`) 
                      VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $user->getFullName(),
                $user->getCpf(),
                $user->getCnpj(),
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
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);            
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
            $query = "UPDATE users SET `full_name` = ?, 
                                       `cpf` = ?, 
                                       `cnpj` = ?, 
                                       `email` = ?, 
                                       `password` = ?, 
                                       `type` = ? 
                                       WHERE id = ?";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $user->getFullName(),
                $user->getCpf(),
                $user->getCnpj(),
                $user->getEmail(),
                $user->getPassword(),
                $user->getType(),
                $user->getId()
            ));

            $conn->commit(); 
            return $conn;
        } catch (Exception $e) {
            if($conn) {
                $conn->rollBack();
            }            
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);            
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
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************
}
