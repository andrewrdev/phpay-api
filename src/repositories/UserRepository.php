<?php

declare(strict_types=1);

namespace src\repositories;

use Exception;
use src\classes\database\DatabaseConnection;
use src\interfaces\repository\Repository;
use PDO;

class UserRepository implements Repository
{

    public static function selectAll()
    {
        $conn = DatabaseConnection::getConnection();

        try {

            $query = "SELECT * FROM users";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                http_response_code(200);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } else {

                http_response_code(404);
                echo json_encode(['message' => 'Users not found']);

            }

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
    }

    public static function selectById(int $id)
    {
        $conn = DatabaseConnection::getConnection();

        try {

            $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($id));

            if ($stmt->rowCount() > 0) {                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {

                http_response_code(404);
                echo json_encode(['message' => 'User not found']);

            }

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
    }

    public static function selectByEmail(string $email)
    {
        $conn = DatabaseConnection::getConnection();

        try {

            $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($email));

            if ($stmt->rowCount() > 0) {

                http_response_code(200);
                return $stmt->fetch(PDO::FETCH_ASSOC);

            } else {

                http_response_code(404);
                echo json_encode(['message' => 'User email not found']);

            }

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
    }

    public static function insert(object $user)
    {
        $conn = DatabaseConnection::getConnection();

        try {
            
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
            http_response_code(201);
            echo json_encode(['message' => 'User created successfully']);

        } catch (Exception $e) {

            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
    }

    public static function update(object $user)
    {
        $conn = DatabaseConnection::getConnection();

        try {

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

            http_response_code(200);
            echo json_encode(['message' => 'User updated successfully']);

        } catch (Exception $e) {

            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
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

            http_response_code(204);
            echo json_encode(['message' => 'User deleted successfully']);

        } catch (Exception $e) {

            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['message' => $e->getMessage()]);

        } finally {

            $conn = null;

        }
    }
}
