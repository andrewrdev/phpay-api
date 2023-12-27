<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
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
            Response::json(['message' => 'Users not found'], 404);             
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
            Response::json(['message' => 'User not found'], 404);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $user)
    {
        if (self::emailExists($user->getEmail())) {
            Response::json(['message' => 'User email already exists'], 409);
        }

        if (self::cpfCnpjExists($user->getCpfCnpj())) {
            Response::json(['message' => 'User cpf_cnpj already exists'], 409);
        }        

        if (UserRepository::insert($user)) {
            Response::json(['message' => 'User created successfully'], 201);
        } else {
            Response::json(['message' => 'User could not be created'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $user)
    {
        if (!self::IdExists($user->getId())) {
            Response::json(['message' => 'User not found'], 404);
        }       

        if (UserRepository::update($user)) {
            Response::json(['message' => 'User updated successfully'], 200);
        } else {
            Response::json(['message' => 'User could not be updated'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        if (!self::IdExists($id)) {
            Response::json(['message' => 'User not found'], 404);      
        }

        if (UserRepository::deleteById($id)) {
            Response::json(['message' => 'User deleted successfully'], 200);
        } else {
            Response::json(['message' => 'User could not be deleted'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deposit(int $userId, float $amount)
    {
        if (!self::IdExists($userId)) {
            Response::json(['message' => 'User not found'], 404);           
        }

        if (UserRepository::updateBalance($userId, $amount)) {
            Response::json(['message' => 'Deposit done successfully'], 200);
        } else {
            Response::json(['message' => 'Deposit could not be done'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getBalance(int $userId)
    {
        if (!self::IdExists($userId)) {
            Response::json(['message' => 'User not found'], 404);         
        } else {
            return UserRepository::selectBalance($userId);
        }        
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getType(int $userId)
    {
        if (!self::IdExists($userId)) {
            Response::json(['message' => 'User not found'], 404);       
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
