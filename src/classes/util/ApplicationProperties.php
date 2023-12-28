<?php

declare(strict_types=1);

namespace src\classes\util;

use src\app\http\Response;

class ApplicationProperties
{
    public static function get(string $key = '')
    {
        $properties = parse_ini_file(__DIR__ . "/../../../src/application.properties");
        if (isset($properties[$key])) {
            return $properties[$key];
        } else {
            Response::json(["error" => "Application property not found"], 500);
        }
    }
}
