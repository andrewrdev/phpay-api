<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class UserValidation
{
    public static function validate(object $user)
    {
        self::validateName($user);
        self::validateEmail($user);
        self::validateCpfCnpj($user);
        self::validatetype($user);
    }

    private static function validateName(object $user)
    {
        if (!preg_match("/^[a-zA-Z ]+$/", $user->getFullName())) {
            Response::json(['message' => 'Name is invalid'], 400);
        }
    }

    private static function validateEmail(object $user)
    {
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            Response::json(['message' => 'Email is invalid'], 400);
        }
    }

    private static function validateCpfCnpj(object $user)
    {
        $cpfRegex = "/^\d{3}\.\d{3}\.\d{3}-\d{2}$/";
        $cnpjRegex = "/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/";

        if (!preg_match($cpfRegex, $user->getCpfCnpj()) && !preg_match($cnpjRegex, $user->getCpfCnpj())) {
            Response::json(['message' => 'cpf_cnpj is invalid'], 400);
        }
    }

    private static function validatetype(object $user)
    {
        if (!in_array($user->getType(), ['common', 'retailer'])) {
            Response::json(['message' => 'Type is invalid, only common or retailer are allowed'], 400);
        }
    }
}
