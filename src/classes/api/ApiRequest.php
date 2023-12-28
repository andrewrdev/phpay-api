<?php

declare(strict_types=1);

namespace src\classes\api;

use src\app\http\Response;
use Exception;

class ApiRequest
{

    public static function get(string $url = '')
    {
        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if ($response !== false) {
                if ($httpCode >= 200 && $httpCode < 300) {
                    return json_decode($response, true);
                } else {
                    Response::json(['message' => 'API request error', 'api_url' => $url], $httpCode);
                }
            } else {
                Response::json(['message' => 'API request error', 'api_url' => $url], $httpCode);
            }
        } catch (Exception $e) {
            Response::json(['message' => $e->getMessage(), 'api_url' => $url], 500);
        }
    }
}
