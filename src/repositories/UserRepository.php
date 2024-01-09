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
    /**************************************************************************
     * Selects all rows from the "users" table.
     *
     * @throws Exception If there is an error executing the query.
     * @return array An array of associative arrays representing the selected rows.
     *************************************************************************/

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

    /**************************************************************************
     * Retrieves the balance of a user by their user ID.
     *
     * @param int $userId The ID of the user.
     * @throws Exception If there is an error retrieving the balance.
     * @return array|null The balance of the user, or null if the user does not exist.
     *************************************************************************/

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

    /**************************************************************************
     * Selects one row from the "users" table.
     * 
     * @param int $id The ID of the user to select.
     * @throws Exception If there is an error executing the query.
     * @return array An associative array representing the selected row.
     *************************************************************************/

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

    /**************************************************************************
     * Selects one row from the "users" table by email.
     * 
     * @param string $email The email of the user to select.
     * @throws Exception If there is an error executing the query.
     * @return array An associative array representing the selected row.
     *************************************************************************/

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

    /**************************************************************************
     * Selects one row from the "users" table by cpf_cnpj.
     * 
     * @param string $cpf_cnpj The cpf_cnpj of the user to select.
     * @throws Exception If there is an error executing the query.
     * @return array An associative array representing the selected row.
     *************************************************************************/
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

    /**************************************************************************
     * Inserts a user object into the database.
     *
     * @param object $user The user object to insert.
     * @throws Exception If an error occurs during the database transaction.
     * @return bool True if the user was inserted successfully, false otherwise.
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }

    /**************************************************************************
     * Updates a user in the database.
     *
     * @param object $user The user object to be updated.
     * @throws Exception If an error occurs during the update process.
     * @return bool True if the user was updated successfully, false otherwise.
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }

    /**************************************************************************
     * Deletes a record from the database by its ID.
     *
     * @param int $id The ID of the record to be deleted.
     * @throws Exception If an error occurs during the deletion process.
     * @return bool True if the record was deleted successfully, false otherwise.
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }

    /**************************************************************************
     * Updates the balance of a user.
     *
     * @param int $userId The ID of the user.
     * @param float $amount The amount to update the balance by.
     * @throws Exception If an error occurs during the update.
     * @return bool True if the balance was updated successfully, false otherwise.
     *************************************************************************/

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
            if ($conn !== null) {
                $conn->rollBack();
            }
            Response::json(['message' => $e->getMessage()], 500);
        } finally {
            $conn = null;
        }
    }
}
