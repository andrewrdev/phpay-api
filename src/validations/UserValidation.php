<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class UserValidation
{
    public static function validate(object $user)
    {
        Validation::validateID($user->getId());
        self::validateFullName($user->getFullName());
        self::validateEmail($user->getEmail());
        self::validateCpfCnpj($user->getCpfCnpj());
        self::validatetype($user->getType());
    }

    public static function validateFullName(string|null $fullName)
    {
        if ($fullName !== null) {
            if (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {
                Response::json(['message' => 'full_name is invalid'], 400);
            }

            if (mb_strlen($fullName) < 3) {
                Response::json(['message' => 'full_name is too short'], 400);
            }
        }
    }

    public static function validateEmail(string|null $email)
    {
        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Response::json(['message' => 'email is invalid'], 400);
            }
        }
    }

    public static function validateCpfCnpj(string|null $cpfCnpj)
    {
        if ($cpfCnpj !== null) {
            $cpfRegex = "/^\d{3}\.\d{3}\.\d{3}-\d{2}$/";
            $cnpjRegex = "/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/";

            if (!preg_match($cpfRegex, $cpfCnpj) && !preg_match($cnpjRegex, $cpfCnpj)) {
                Response::json(['message' => 'cpf_cnpj is invalid'], 400);
            }
        }
    }

    public static function validatetype(string|null $type)
    {
        if ($type !== null) {
            if (!in_array($type, ['common', 'retailer'])) {
                Response::json(['message' => 'type is invalid, only common or retailer are allowed'], 400);
            }
        }
    }
}
