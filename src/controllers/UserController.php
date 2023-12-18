<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\classes\database\DatabaseConnection;
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
        $response->json($users);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function insert(Request $request, Response $response)
    {
        $id = (int) $request->getParam('id');
        $users = UserRepository::selectById($id);
        $response->json($users);
    }
    
    // ********************************************************************************************
    // ********************************************************************************************

    public function update(Request $request, Response $response)
    {
        $id = (int) $request->getParam('id');
        $users = UserRepository::selectById($id);
        $response->json($users);
    }
    
    // ********************************************************************************************
    // ********************************************************************************************

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');
        $users = UserRepository::deleteById($id);
        $response->json($users);
    }
    
    // ********************************************************************************************
    // ********************************************************************************************
}
