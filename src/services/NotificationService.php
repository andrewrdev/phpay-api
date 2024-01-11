<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
use src\classes\api\ApiRequest;
use src\repositories\NotificationRepository;

class NotificationService
{
    /**************************************************************************
     * Select all notifications.
     *
     * @return mixed
     *************************************************************************/

    public static function selectAll()
    {
        $notifications = NotificationRepository::selectAll();

        return (!empty($notifications)) ? $notifications : Response::json(['message' => 'Notifications not found'], 404);
    }

    /**************************************************************************
     * Select a notification by its ID.
     *
     * @param int $id The ID of the notification.
     * @return mixed The selected notification or a JSON response with 
     * a 'Notification not found' message and a 404 status code.
     *************************************************************************/

    public static function selectById(int $id)
    {
        $notification = NotificationRepository::selectById($id);

        return (!empty($notification)) ? $notification : Response::json(['message' => 'Notification not found'], 404);
    }

    /**************************************************************************
     * Sends a notification.
     *
     * @param object $notification The notification object to be sent.     
     * @return void
     *************************************************************************/

    public static function sendNotification(object $notification)
    {
        self::checkIfNotificationIsAuthorized();

        if (!NotificationRepository::insert($notification)) {
            Response::json(['message' => 'Notification could not be created'], 500);
        }
    }

    /**************************************************************************
     * Update a notification.
     *
     * @param object $notification The notification object to update.     
     * @return void
     *************************************************************************/

    public static function update(object $notification)
    {
        self::checkIfNotificationExists($notification->getId());

        if (NotificationRepository::update($notification)) {
            Response::json(['message' => 'Notification updated successfully'], 200);
        } else {
            Response::json(['message' => 'Notification could not be updated'], 500);
        }
    }

    /**************************************************************************
     * Deletes a notification by its ID.
     *
     * @param int $id The ID of the notification to be deleted.     
     * @return void
     *************************************************************************/

    public static function deleteById(int $id)
    {
        self::checkIfNotificationExists($id);

        if (NotificationRepository::deleteById($id)) {
            Response::json(['message' => 'Notification deleted successfully'], 200);
        } else {
            Response::json(['message' => 'Notification could not be deleted'], 500);
        }
    }

    /**************************************************************************
     * Checks if a notification exists.
     *
     * @param int $id The ID of the notification.     
     * @return void
     *************************************************************************/

    private static function checkIfNotificationExists(int $id): void
    {
        $notification = NotificationRepository::selectById($id);

        if (empty($notification)) {
            Response::json(['message' => 'Notification not found'], 404);
        }
    }

    /**************************************************************************
     * Checks if the notification is authorized.
     *     
     * @return void
     *************************************************************************/

    private static function checkIfNotificationIsAuthorized(): void
    {
        $response = ApiRequest::get('https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6');

        if (!empty($response)) {
            if ($response['message'] !== true) {
                Response::json(['message' => 'Notification not authorized'], 503);
            }
        } else {
            Response::json(['message' => 'Notification not authorized'], 503);
        }
    }
}
