<?php

declare(strict_types=1);

namespace src\validations;

use src\app\http\Response;

class UserValidation
{
    /**************************************************************************
     * Validates the given user object.
     *
     * @param object $user The user object to be validated.
     * @return void
     *************************************************************************/

    public static function validate(object $user)
    {
        Validation::validateID($user->getId());
        self::validateFullName($user->getFullName());
        self::validateEmail($user->getEmail());
        self::validateCpfCnpj($user->getCpfCnpj());
        self::validatetype($user->getType());
    }

    /**************************************************************************
     * Validates a full name.
     *
     * @param string|null $fullName The full name to be validated.
     * @return void
     *************************************************************************/

    public static function validateFullName(?string $fullName)
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

    /**************************************************************************
     * Validates an email address.
     *
     * @param string|null $email The email address to validate.
     * @return void     
     *************************************************************************/

    public static function validateEmail(?string $email)
    {
        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Response::json(['message' => 'email is invalid'], 400);
            }
        }
    }

    /**************************************************************************
     * Validates a given CPF (Cadastro de Pessoas Físicas) or 
     * CNPJ (Cadastro Nacional da Pessoa Jurídica) number.
     *
     * @param string|null $cpfCnpj The CPF or CNPJ number to be validated.
     * @return void
     *************************************************************************/

    public static function validateCpfCnpj(?string $cpfCnpj)
    {
        if ($cpfCnpj !== null) {
            $cpfRegex = "/^\d{3}\.\d{3}\.\d{3}-\d{2}$/";
            $cnpjRegex = "/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/";

            if (!preg_match($cpfRegex, $cpfCnpj) && !preg_match($cnpjRegex, $cpfCnpj)) {
                Response::json(['message' => 'cpf_cnpj is invalid'], 400);
            }
        }
    }

    /**************************************************************************
     * Validates the type parameter.
     *
     * @param ?string $type The type parameter to be validated.
     * @return void
     *************************************************************************/
    public static function validatetype(?string $type)
    {
        if ($type !== null) {
            if (!in_array($type, ['common', 'retailer'])) {
                Response::json(['message' => 'type is invalid, only common or retailer are allowed'], 400);
            }
        }
    }
}
