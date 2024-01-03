<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class TransactionValidation
{
    public static function validate(object $transaction)
    {
        Validation::validateID($transaction->getSenderId(), 'sender_id is invalid');
        Validation::validateID($transaction->getReceiverId(), 'receiver_id is invalid');
        self::validateAmount($transaction->getAmount());
    }

    public static function validateAmount(float|null $amount)
    {
        if ($amount != null) {
            if ($amount <= 0 || !is_numeric($amount) || !filter_var($amount, FILTER_VALIDATE_FLOAT)) {
                Response::json(['message' => 'amount is invalid'], 400);
            }
        }
    }
}
