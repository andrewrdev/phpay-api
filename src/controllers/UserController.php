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

public function index(Request $request, Response $response)
{  
    $id = (int) $request->getParam('id');
    $users = UserRepository::selectById($id);
    $response->json($users);  
    
    
}
    // ********************************************************************************************
    // ********************************************************************************************
}