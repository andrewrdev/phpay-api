<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\classes\database\DatabaseConnection;

class HomeController
{
    /**************************************************************************
     * Index function to handle the request and response.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.     
     *************************************************************************/

    public function index(Request $request, Response $response)
    {
        if (DatabaseConnection::getConnection()) {
            Response::json(["api_running" => true, "version" => '1.0', "statusCode" => 200]);
        }
    }
}
