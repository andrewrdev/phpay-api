<?php

declare(strict_types=1);

namespace src\models;

class UserModel
{

    private int $id;
    private string $fullName;
    private string $cpf;
    private string $cnpj;
    private string $email;
    private string $password;
    private float $balance;
    private string $type;
    
    public function getId(): int {
        return $this->id;
    }
    
    public function setId(int $id): void {
        $this->id = $id;
    }
   
    public function getFullName(): string {
        return $this->fullName;
    }
    
    public function setFullName(string $fullName): void {
        $this->fullName = $fullName;
    }
    
    public function getCpf(): string {
        return $this->cpf;
    }
    
    public function setCpf(string $cpf): void {
        $this->cpf = $cpf;        
    }
    
    public function getCnpj(): string {
        return $this->cnpj;
    }
    
    public function setCnpj(string $cnpj): void {
        $this->cnpj = $cnpj;        
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function setEmail(string $email): void {
        $this->email = $email;        
    }
    
    public function getPassword(): string {
        return $this->password;
    }
    
    public function setPassword(string $password): void {
        $this->password = $password;        
    }
    
    public function getBalance(): float {
        return $this->balance;
    }
    
    public function setBalance(float $balance): void {
        $this->balance = $balance;        
    }
    
    public function getType(): string {
        return $this->type;
    }
    
    public function setType(string $type): void {
        $this->type = $type;        
    }
}
