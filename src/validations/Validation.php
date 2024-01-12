<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class Validation
{
    /**************************************************************************
     * Validates the given ID.
     *
     * @param int|null $id The ID to validate.
     * @param string $message The message to display if the ID is invalid. 
     * Default is 'id is invalid'.
     *      
     * @return void
     *************************************************************************/

    public static function validateID(?int $id, string $message = 'id is invalid')
    {
        if ($id !== null) {
            if (!filter_var($id, FILTER_VALIDATE_INT) || $id < 1) {
                Response::json(['message' => $message], 400);
            }
        }
    }
}
