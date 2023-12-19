<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\models\UserModel;
use src\repositories\UserRepository;

class UserController
{

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectAll(Request $request, Response $response)
    {  
        $users = UserRepository::selectAll();
        $response->json($users);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->pathParam('id');
        $users = UserRepository::selectById($id);

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
        $user->setCpf($request->getParam('cpf'));
        $user->setCnpj($request->getParam('cnpj'));
        $user->setEmail($request->getParam('email'));
        $user->setPassword($request->getParam('password'));
        $user->setType($request->getParam('type'));

        UserRepository::insert($user);        
    }
    
    // ********************************************************************************************
    // ********************************************************************************************

    public function update(Request $request, Response $response)
    {
        $user = new UserModel();
        $user->setFullName($request->getParam('full_name'));
        $user->setCpf($request->getParam('cpf'));
        $user->setCnpj($request->getParam('cnpj'));
        $user->setEmail($request->getParam('email'));
        $user->setPassword($request->getParam('password'));
        $user->setType($request->getParam('type'));
        $user->setId((int) $request->pathParam('id'));

        UserRepository::update($user);            
    }
    
    // ********************************************************************************************
    // ********************************************************************************************

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');
        UserRepository::deleteById($id);        
    }
    
    // ********************************************************************************************
    // ********************************************************************************************
}
