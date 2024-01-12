<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class TransactionValidation
{
    /**************************************************************************
     * Validates a transaction object.
     *
     * @param object $transaction The transaction object to validate.     
     * @return void
     *************************************************************************/

    public static function validate(object $transaction)
    {
        Validation::validateID($transaction->getSenderId(), 'sender_id is invalid');
        Validation::validateID($transaction->getReceiverId(), 'receiver_id is invalid');
        self::validateAmount($transaction->getAmount());
    }

    /**************************************************************************
     * Validates the amount.
     *
     * @param float|null $amount The amount to be validated.
     * @return void
     *************************************************************************/

    public static function validateAmount(?float $amount)
    {
        if ($amount != null) {
            if ($amount <= 0 || !is_numeric($amount) || !filter_var($amount, FILTER_VALIDATE_FLOAT)) {
                Response::json(['message' => 'amount is invalid'], 400);
            }
        }
    }
}
