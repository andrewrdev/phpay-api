<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\models\UserModel;
use src\services\UserService;
use src\validations\TransactionValidation;
use src\validations\UserValidation;
use src\validations\Validation;

class UserController
{
    /**************************************************************************
     * Selects all users and returns them as a JSON response.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     *************************************************************************/

    public function selectAll(Request $request, Response $response)
    {
        $users = UserService::selectAll();

        if (!empty($users)) {
            $response->json($users);
        }
    }

    /**************************************************************************
     * Retrieves a single user by their ID.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.     
     *************************************************************************/

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id, 'user_id is invalid');
        $users = UserService::selectById($id);

        if (!empty($users)) {
            $response->json($users);
        }
    }

    /**************************************************************************
     * Inserts a new user into the system.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.     
     *************************************************************************/

    public function insert(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setFullName((string) $request->getParam('full_name'));
        $user->setCpfCnpj((string) $request->getParam('cpf_cnpj'));
        $user->setEmail((string) $request->getParam('email'));
        $user->setPassword((string) $request->getParam('password'));
        $user->setType((string) $request->getParam('type'));

        UserValidation::validate($user);
        UserService::insert($user);
    }

    /**************************************************************************
     * Updates a user based on the provided request and response objects.
     *
     * @param Request $request The request object containing the data to 
     * update the user.
     * 
     * @param Response $response The response object used to send a response 
     * after the update is complete.     
     *************************************************************************/

    public function update(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setId((int) $request->pathParam('id'));
        $user->setFullName((string) $request->getParam('full_name'));
        $user->setPassword((string) $request->getParam('password'));

        UserValidation::validate($user);
        UserService::update($user);
    }

    /**************************************************************************
     * Deletes a user by ID.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.     
     *************************************************************************/

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id, 'user_id is invalid');
        UserService::deleteById($id);
    }

    /**************************************************************************
     * Deposit money into user's account.
     *
     * @param Request $request The request object containing user_id and amount.
     * @param Response $response The response object.     
     *************************************************************************/

    public function deposit(Request $request, Response $response)
    {
        $user_id = (int) $request->getParam('user_id');
        $amount = (float) $request->getParam('amount');

        Validation::validateID($user_id);
        TransactionValidation::validateAmount($amount);

        UserService::deposit($user_id, $amount);
    }
}
