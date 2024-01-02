<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\models\UserModel;
use src\services\UserService;
use src\validations\UserValidation;

class UserController
{

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectAll(Request $request, Response $response)
    {
        $users = UserService::selectAll();

        if (!empty($users)) {
            $response->json($users);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->pathParam('id');
        $users = UserService::selectById($id);
        if (!empty($users)) {
            $response->json($users);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function insert(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setFullName($request->getParam('full_name'));
        $user->setCpfCnpj($request->getParam('cpf_cnpj'));
        $user->setEmail($request->getParam('email'));
        $user->setPassword($request->getParam('password'));
        $user->setType($request->getParam('type'));

        UserValidation::validate($user);

        UserService::insert($user);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function update(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setFullName($request->getParam('full_name'));
        $user->setPassword($request->getParam('password'));
        $user->setId((int) $request->pathParam('id'));

        UserValidation::validate($user);

        UserService::update($user);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function delete(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setId((int) $request->pathParam('id'));   
        
        UserValidation::validateId($user);
        
        UserService::deleteById($user->getId());
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function deposit(Request $request, Response $response)
    {
        $user_id = (int) $request->getParam('user_id');
        $amount = (float) $request->getParam('amount');

        UserService::deposit($user_id, $amount);
    }

    // ********************************************************************************************
    // ********************************************************************************************
}
