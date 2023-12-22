<?php

declare(strict_types=1);

namespace src\repositories;

use Exception;
use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use PDO;

class TransactionRepository implements Repository
{
    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT * FROM transactions";
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
            $query = "SELECT * FROM transactions WHERE id = ? LIMIT 1";
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

    public static function insert(object $transaction)
    {
        $conn = null; 
        try {
            $conn = DatabaseConnection::getConnection();            
            $conn->beginTransaction();
            $query = "INSERT INTO transactions (`amount`,`sender_id`, `receiver_id`) 
                      VALUES (?, ?, ?)";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $transaction->getAmount(),
                $transaction->getSenderId(),
                $transaction->getReceiverId()
            ));

            $query = "UPDATE users SET balance = balance + ? WHERE id = ?";  
            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $transaction->getAmount(),
                $transaction->getReceiverId()
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

    public static function update(object $transaction)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            $query = "UPDATE transactions SET `amount` = ?, `sender_id` = ?, `receiver_id` = ? WHERE id = ?";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $transaction->getAmount(),
                $transaction->getSenderId(),
                $transaction->getReceiverId(),
                $transaction->getId()
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
            $query = "DELETE FROM transactions WHERE id = ?";
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
