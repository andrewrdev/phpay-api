<?php

declare(strict_types=1);

namespace src\repositories;

use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use src\app\http\Response;
use PDO;
use Exception;

class TransactionRepository implements Repository
{
    /**************************************************************************
     * Retrieves all transactions from the database.
     *
     * @throws Exception when there is an error executing the query
     * @return array An array of transactions     
     *************************************************************************/

    public static function selectAll()
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT 
                 transactions.id,
                 transactions.amount, 
                 sender.full_name AS sender_name,
                 sender.cpf_cnpj AS sender_cpf_cnpj,                 
                 receiver.full_name AS receiver_name,
                 receiver.cpf_cnpj AS receiver_cpf_cnpj,                 
                 transactions.created_at
                FROM transactions
                INNER JOIN users AS sender 
                ON transactions.sender_id = sender.id
                INNER JOIN users AS receiver 
                ON transactions.receiver_id = receiver.id";

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


    /**************************************************************************
     * Retrieves a transaction by its ID from the database.
     *
     * @param int $id The ID of the transaction to retrieve.
     * @throws Exception If there is an error retrieving the transaction.
     * @return bool True if the transaction was retrieved successfully, 
     * false otherwise.
     *************************************************************************/

    public static function selectById(int $id)
    {
        $conn = null;
        try {
            $conn = DatabaseConnection::getConnection();
            $query = "SELECT 
                 transactions.id,
                 transactions.amount, 
                 sender.full_name AS sender_name,
                 sender.cpf_cnpj AS sender_cpf_cnpj,                 
                 receiver.full_name AS receiver_name,
                 receiver.cpf_cnpj AS receiver_cpf_cnpj,                 
                 transactions.created_at
                FROM transactions
                INNER JOIN users AS sender 
                ON transactions.sender_id = sender.id
                INNER JOIN users AS receiver 
                ON transactions.receiver_id = receiver.id WHERE transactions.id = ? LIMIT 1";
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

    /**************************************************************************
     * Inserts a new transaction in the database.
     * 
     * @param object $transaction The transaction to insert.
     * @throws Exception when there is an error executing the query
     * @return boolean True if the transaction was inserted successfully     
     *************************************************************************/

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

            $query = "UPDATE users SET balance = balance - ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array(
                $transaction->getAmount(),
                $transaction->getSenderId()
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

    /**************************************************************************
     * Updates a transaction in the database.
     * 
     * @throws Exception when there is an error executing the query
     * @param object $transaction The transaction to update.
     * @return boolean True if the transaction was updated successfully     
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }

            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }

    /**************************************************************************
     * Deletes a transaction from the database.
     * 
     * @throws Exception when there is an error executing the query
     * @param int $id The ID of the transaction to delete.
     * @return boolean True if the transaction was deleted successfully     
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }

            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }
}
