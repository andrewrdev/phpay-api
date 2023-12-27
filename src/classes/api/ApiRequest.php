<?php 

declare(strict_types=1);

namespace classes\api;

use src\app\http\Response;

class ApiRequest
{

    public static function get(string $url = '')
    {
        $request = curl_init($url);

        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($request);

        curl_close($request);

        if ($response !== false) {
            Response::json(['response' => $response], 200);
        } else {
            Response::json(['message' => 'API request error: ' . curl_error($request)], 503);
        }
    }
}