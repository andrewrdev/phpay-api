<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\services\NotificationService;
use src\validations\Validation;

class NotificationController
{
    /**************************************************************************
     * Selects all notifications.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     *************************************************************************/

    public function selectAll(Request $request, Response $response)
    {
        $notifications = NotificationService::selectAll();

        if (!empty($notifications)) {
            $response->json($notifications);
        }
    }

    /**************************************************************************
     * Retrieves a single notification by its ID.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.     
     *************************************************************************/

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateId($id);
        $notifications = NotificationService::selectById($id);

        $response->json($notifications);
    }

    /**************************************************************************
     * Deletes a notification by ID.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.     
     *************************************************************************/

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateId($id);
        NotificationService::deleteById($id);
    }
}
