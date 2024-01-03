<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class Validation
{
    public static function validateID(int|null $id, string $message = 'id is invalid')
    {
        if ($id !== null) {
            if (!filter_var($id, FILTER_VALIDATE_INT) || $id < 1) {
                Response::json(['message' => $message], 400);
            }
        }
    }
}
