<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class Validation
{
    public static function validateId(int $id)
    {
        if (!is_numeric($id) && $id <= 0 && is_int($id)) {
            Response::json(['message' => 'Id is invalid'], 400);
        }
    }    
}