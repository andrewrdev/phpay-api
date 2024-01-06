<?php

declare(strict_types=1);

namespace src\classes\util;

use src\app\http\Response;

class ApplicationProperties
{
    /**************************************************************************
     * Retrieves a value from the application properties file based 
     * on the given key.
     *
     * @param string $key The key to look up in the application properties file. 
     * Defaults to an empty string.
     * 
     * @return mixed The value corresponding to the given key in the 
     * application properties file, or null if the key is not found.
     *************************************************************************/
    
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
