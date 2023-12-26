<?php 

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\classes\database\DatabaseConnection;

class HomeController
{    

    // ********************************************************************************************
    // ********************************************************************************************

    public function index(Request $request, Response $response)
    {                  
        if(DatabaseConnection::getConnection()) 
        {
            Response::json(["api_running" => true, "version" => '1.0', "statusCode" => 200]);
        }     
    }    

    // ********************************************************************************************
    // ********************************************************************************************
}