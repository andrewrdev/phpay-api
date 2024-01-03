<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\services\NotificationService;
use src\validations\Validation;

class NotificationController
{

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectAll(Request $request, Response $response)
    {
        $notifications = NotificationService::selectAll();

        if (!empty($notifications)) {
            $response->json($notifications);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateId($id);
        $notifications = NotificationService::selectById($id);
        
        $response->json($notifications);
    }

    // ********************************************************************************************
    // ******************************************************************************************** 

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateId($id);
        NotificationService::deleteById($id);
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
