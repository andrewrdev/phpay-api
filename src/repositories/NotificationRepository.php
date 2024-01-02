<?php

declare(strict_types=1);

namespace src\repositories;

use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use src\app\http\Response;
use PDO;
use Exception;

class NotificationRepository implements Repository
{
    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT 
                notifications.id,                 
                sender.full_name AS sender_name,                                  
                receiver.full_name AS receiver_name,                                  
                notifications.message,
                notifications.date
                FROM notifications
                INNER JOIN users AS sender 
                ON notifications.sender_id = sender.id
                INNER JOIN users AS receiver 
                ON notifications.receiver_id = receiver.id";

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

    public static function selectById(int $id)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT 
                notifications.id,                                
                sender.full_name AS sender_name,                                                 
                receiver.full_name AS receiver_name,                                  
                notifications.message,
                notifications.date
                FROM notifications
                INNER JOIN users AS sender 
                ON notifications.sender_id = sender.id
                INNER JOIN users AS receiver 
                ON notifications.receiver_id = receiver.id WHERE notifications.id = ? LIMIT 1";
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

    public static function insert(object $notification)
    {
        $conn = null;

        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();

            $query = "INSERT INTO notifications (`sender_id`, `receiver_id`, `message`) 
                      VALUES (?, ?, ?)";

            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $notification->getSenderId(),
                $notification->getReceiverId(),
                $notification->getMessage()
            ));           

            $conn->commit();

            return $conn;
        } catch (Exception $e) {
            if ($conn !== null) {
                $conn->rollBack();
            }

            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************   

    public static function update(object $notification)
    {
        return null;        
    }

    // ********************************************************************************************
    // ********************************************************************************************   

    public static function deleteById(int $id)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            $query = "DELETE FROM notifications WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($id));
            $conn->commit();
            return $conn;
        } catch (Exception $e) {
            if ($conn !== null) {
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
