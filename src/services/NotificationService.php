<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
use src\classes\api\ApiRequest;
use src\repositories\NotificationRepository;

class NotificationService
{

    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $transactions = NotificationRepository::selectAll();

        return (!empty($transactions)) ? $transactions : Response::json(['message' => 'Notifications not found'], 404);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $transaction = NotificationRepository::selectById($id);

        return (!empty($transaction)) ? $transaction : Response::json(['message' => 'Notification not found'], 404);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function sendNotification(object $notification)
    {
        self::checkIfNotificationIsAuthorized();

        if (NotificationRepository::insert($notification)) {
            Response::json(['message' => 'Notification created successfully'], 201);
        } else {
            Response::json(['message' => 'Notification could not be created'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $notification)
    {
        self::checkIfNotificationExists($notification->getId());

        if (NotificationRepository::update($notification)) {
            Response::json(['message' => 'Notification updated successfully'], 200);
        } else {
            Response::json(['message' => 'Notification could not be updated'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        self::checkIfNotificationExists($id);

        if (NotificationRepository::deleteById($id)) {
            Response::json(['message' => 'Notification deleted successfully'], 200);
        } else {
            Response::json(['message' => 'Notification could not be deleted'], 500);
        }
    }

    // ********************************************************************************************
    // ******************************************************************************************** 

    private static function checkIfNotificationExists(int $id): void
    {
        $notification = NotificationRepository::selectById($id);

        if (empty($notification)) {
            Response::json(['message' => 'Notification not found'], 404);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfNotificationIsAuthorized(): void
    {
        $response = ApiRequest::get('https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6');

        if (!empty($response)) {
            if ($response['message'] !== true) {
                Response::json(['message' => 'Notification not authorized'], 401);
            }
        } else {
            Response::json(['message' => 'Notification not authorized'], 401);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
