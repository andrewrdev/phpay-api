<?php 

declare(strict_types = 1);

namespace src\classes\util;

class ApplicationProperties
{
    public static function get(string $key = '')
    {
        $properties = parse_ini_file(__DIR__ . "/../../../src/application.properties");
        if(isset($properties[$key])) {
            return $properties[$key];
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Application property not found"]);
            exit;
        }
        
    }
}