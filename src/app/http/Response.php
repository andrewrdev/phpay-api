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
                    $message['success'] = 'Created';    
                    break;
                case 400:
                    $message['error'] = 'Bad Request';
                    break;                
                case 409:
                    $message['error'] = 'Conflict';
                    break;                
                case 500:
                    $message['error'] = 'Internal Server Error';
                    break;
                case 503:
                    $message['error'] = 'Service Unavailable';
                    break;                
                case 404:
                    $message['error'] = 'Not Found';  
                    break;
                case 500:
                    $message['error'] = 'Internal Server Error';
                    break; 
                default:
                    $message['success'] = 'OK';
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
