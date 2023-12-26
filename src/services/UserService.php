<?php

declare(strict_types=1);

namespace src\services;

use src\repositories\UserRepository;

class UserService
{

    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $users = UserRepository::selectAll();

        if (!empty($users)) {
            return $users;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Users not found', 'statusCode' => 404]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $user = UserRepository::selectById($id);
        if (!empty($user)) {
            return $user;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found', 'statusCode' => 404]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $user)
    {
        if (self::emailExists($user->getEmail())) {
            http_response_code(409);
            echo json_encode(['message' => 'User email already exists', 'statusCode' => 409]);
            exit;
        }

        if (self::cpfCnpjExists($user->getCpfCnpj())) {
            http_response_code(409);
            echo json_encode(['message' => 'User cpf_cnpj already exists', 'statusCode' => 409]);
            exit;
        }        

        if (UserRepository::insert($user)) {
            http_response_code(201);
            echo json_encode(['message' => 'User created successfully', 'statusCode' => 201]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be created', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $user)
    {
        if (!self::IdExists($user->getId())) {
            http_response_code(404);
            echo json_encode(['message' => 'Error updating user - User not found', 'statusCode' => 404]);
            exit;
        }       

        if (UserRepository::update($user)) {
            http_response_code(201);
            echo json_encode(['message' => 'User updated successfully', 'statusCode' => 201]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be updated', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        if (!self::IdExists($id)) {
            http_response_code(404);
            echo json_encode(['message' => 'Error deleting user - User not found', 'statusCode' => 404]);
            exit;            
        }

        if (UserRepository::deleteById($id)) {
            http_response_code(200);
            echo json_encode(['message' => 'User deleted successfully', 'statusCode' => 200]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be deleted', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deposit(int $userId, float $amount)
    {
        if (!self::IdExists($userId)) {
            http_response_code(404);
            echo json_encode(['message' => 'Error depositing - User not found', 'statusCode' => 404]);
            exit;            
        }

        if (UserRepository::updateBalance($userId, $amount)) {
            http_response_code(200);
            echo json_encode(['message' => 'Deposit successfully', 'statusCode' => 200]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Deposit could not be done', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getBalance(int $userId)
    {
        if (!self::IdExists($userId)) {
            http_response_code(404);
            echo json_encode(['message' => 'Error getting balance - User not found', 'statusCode' => 404]);
            exit;            
        } else {
            return UserRepository::selectBalance($userId);
        }        
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getType(int $userId)
    {
        if (!self::IdExists($userId)) {
            http_response_code(404);
            echo json_encode(['message' => 'Error getting user type - User not found', 'statusCode' => 404]);
            exit;            
        } else {
            return UserRepository::selectById($userId);
        }        
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function IdExists(int $id): bool
    {
        $user = UserRepository::selectById($id);
        return (!empty($user)) ? true : false;
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function emailExists(string $email): bool
    {
        $user = UserRepository::selectByEmail($email);
        return (!empty($user)) ? true : false;
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function cpfCnpjExists(string $cpf_cnpj): bool
    {
        $user = UserRepository::selectByCpfCnpj($cpf_cnpj);
        return (!empty($user)) ? true : false;
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
