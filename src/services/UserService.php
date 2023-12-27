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

        return (!empty($users)) ? $users : Response::json(['message' => 'Users not found'], 404);        
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $user = UserRepository::selectById($id);

        return (!empty($user)) ? $user : Response::json(['message' => 'User not found'], 404);  
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $user)
    {
        self::checkIfEmailExists($user->getEmail());
        self::checkIfCpfCnpjExists($user->getCpfCnpj());              

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
        self::checkIfIdExists($user->getId());

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
        self::checkIfIdExists($id);

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
        self::checkIfIdExists($userId);

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
        self::checkIfIdExists($userId);

        return UserRepository::selectBalance($userId);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function getType(int $userId)
    {
        self::checkIfIdExists($userId);
     
        return UserRepository::selectById($userId);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function checkIfIdExists(int $id): void
    {
        $user = UserRepository::selectById($id);
        
        if(empty($user)) {
            Response::json(['message' => 'User not found'], 404);
        } 
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfEmailExists(string $email): void
    {
        $user = UserRepository::selectByEmail($email);

        if(empty($user)) {
            Response::json(['message' => 'User email already exists'], 409);
        } 
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfCpfCnpjExists(string $cpf_cnpj): void
    {
        $user = UserRepository::selectByCpfCnpj($cpf_cnpj);
        
        if(empty($user)) {
            Response::json(['message' => 'User cpf_cnpj already exists'], 409);
        } 
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
