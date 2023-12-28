<?php

declare(strict_types=1);

namespace src\app\http;

class Response
{
    // ********************************************************************************************
    // ********************************************************************************************

    public static function json(array $message = [], int $statusCode = 200): void
    {
        if (!empty($message)) {

            switch ($statusCode) {
                case 201:
                    $message['status'] = 'Created';
                    $message['statusCode'] = 201;
                    break;
                case 400:
                    $message['status'] = 'Bad Request';
                    $message['statusCode'] = 400;
                    break;
                case 401:
                    $message['status'] = 'Unauthorized';
                    $message['statusCode'] = 401;
                    break;
                case 404:
                    $message['status'] = 'Not Found';
                    $message['statusCode'] = 404;
                    break;
                case 409:
                    $message['status'] = 'Conflict';
                    $message['statusCode'] = 409;
                    break;
                case 500:
                    $message['status'] = 'Internal Server Error';
                    $message['statusCode'] = 500;
                    break;
                case 503:
                    $message['status'] = 'Service Unavailable';
                    $message['statusCode'] = 503;
                    break;
            }

            header('Content-Type: application/json');
            http_response_code($statusCode);
            echo json_encode($message);
            exit;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************
}
