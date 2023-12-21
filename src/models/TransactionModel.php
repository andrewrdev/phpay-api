<?php

declare(strict_types=1);

namespace src\models;

class TransactionModel
{

    private int $id;
    private int $senderId;
    private int $receiverId;
    private float $amount;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;        
    }

    public function getSenderId(): int {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void {
        $this->senderId = $senderId;        
    }

    public function getReceiverId(): int {
        return $this->receiverId;
    }
    
    public function setReceiverId(int $receiverId): void {
        $this->receiverId = $receiverId;        
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function setAmount(float $amount): void {
        $this->amount = $amount;        
    }
}
