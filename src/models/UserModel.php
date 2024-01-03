<?php

declare(strict_types=1);

namespace src\models;

class UserModel
{

    private int|null $id;
    private string|null $fullName;
    private string|null $cpf_cnpj;
    private string|null $email;
    private string|null $password;
    private float|null $balance;
    private string|null $type;

    public function __construct()
    {
        $this->id = null;
        $this->fullName = null;
        $this->cpf_cnpj = null;
        $this->email = null;
        $this->password = null;
        $this->balance = null;
        $this->type = null;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFullName(): string|null
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = ucwords($fullName);
    }

    public function getCpfCnpj(): string|null
    {
        return $this->cpf_cnpj;
    }

    public function setCpfCnpj(string $cpf_cnpj): void
    {
        $this->cpf_cnpj = $cpf_cnpj;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getBalance(): float|null
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getType(): string|null
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = strtolower($type);
    }
}
