<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
use src\repositories\UserRepository;

class UserService
{
    /**************************************************************************
     * Selects all users from the UserRepository.
     *
     * @return datatype The selected users or a JSON response if no users found
     *************************************************************************/

    public static function selectAll()
    {
        $users = UserRepository::selectAll();

        return (!empty($users)) ? $users : Response::json(['message' => 'Users not found'], 404);
    }

    /**************************************************************************
     * Retrieves a user by their ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return mixed The user object if found, or a JSON response with 
     * an error message and status code 404 if not found.
     *************************************************************************/
    public static function selectById(int $id)
    {
        $user = UserRepository::selectById($id);

        return (!empty($user)) ? $user : Response::json(['message' => 'User not found'], 404);
    }

    /**************************************************************************
     * Insert a user into the database.
     *
     * @param object $user The user object to be inserted. 
     * @return void
     *************************************************************************/

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

    /**************************************************************************
     * Updates a user.
     *
     * @param object $user The user object to be updated.     
     * @return void
     *************************************************************************/

    public static function update(object $user)
    {
        self::checkIfUserExists($user->getId());

        if (UserRepository::update($user)) {
            Response::json(['message' => 'User updated successfully'], 200);
        } else {
            Response::json(['message' => 'User could not be updated'], 500);
        }
    }

    /**************************************************************************
     * Deletes a user by their ID.
     *
     * @param int $id The ID of the user to be deleted.     
     * @return void
     *************************************************************************/

    public static function deleteById(int $id)
    {
        self::checkIfUserExists($id);

        if (UserRepository::deleteById($id)) {
            Response::json(['message' => 'User deleted successfully'], 200);
        } else {
            Response::json(['message' => 'User could not be deleted'], 500);
        }
    }

    /**************************************************************************
     * Deposits a given amount into a user's account.
     *
     * @param int $userId The ID of the user.
     * @param float $amount The amount to deposit.     
     * @return void
     *************************************************************************/

    public static function deposit(int $userId, float $amount)
    {
        self::checkIfUserExists($userId);

        if (UserRepository::updateBalance($userId, $amount)) {
            Response::json(['message' => 'Deposit done successfully'], 200);
        } else {
            Response::json(['message' => 'Deposit could not be done'], 500);
        }
    }

    /**************************************************************************
     * Retrieves the balance of a user.
     *
     * @param int $userId The ID of the user.     
     * @return Some_Return_Value The balance of the user.
     *************************************************************************/

    public static function getBalance(int $userId)
    {
        self::checkIfUserExists($userId);

        return UserRepository::selectBalance($userId);
    }

    /**************************************************************************
     * Retrieves the type of a user based on their ID.
     *
     * @param int $userId The ID of the user.     
     * @return mixed The type of the user.
     *************************************************************************/

    public static function getType(int $userId)
    {
        self::checkIfUserExists($userId);

        return UserRepository::selectById($userId);
    }

    /**************************************************************************
     * Checks if a user with the given ID exists and returns a JSON response 
     * if not found.
     *
     * @param int $id The ID of the user to check.
     * @param string $message The message to include in the JSON response if 
     * the user is not found. Defaults to 'User not found'.
     * @return void
     *************************************************************************/

    public static function checkIfUserExists(int $id, string $message = 'User not found'): void
    {
        $user = UserRepository::selectById($id);

        if (empty($user)) {
            Response::json(['message' => $message], 404);
        }
    }

    /**************************************************************************
     * Checks if the given email already exists in the user repository.
     *
     * @param string $email The email to check.     
     * @return void
     *************************************************************************/

    private static function checkIfEmailExists(string $email): void
    {
        $user = UserRepository::selectByEmail($email);

        if (!empty($user)) {
            Response::json(['message' => 'User email already exists'], 409);
        }
    }

    /**************************************************************************
     * Check if a CPF or CNPJ already exists in the user repository.
     *
     * @param string $cpf_cnpj The CPF or CNPJ to check.     
     * @return void
     *************************************************************************/

    private static function checkIfCpfCnpjExists(string $cpf_cnpj): void
    {
        $user = UserRepository::selectByCpfCnpj($cpf_cnpj);

        if (!empty($user)) {
            Response::json(['message' => 'User cpf_cnpj already exists'], 409);
        }
    }
}
