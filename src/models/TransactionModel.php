<?php

declare(strict_types=1);

namespace src\models;

class TransactionModel
{

    private int|null $id;
    private int|null $senderId;
    private int|null $receiverId;
    private float|null $amount;

    public function __construct()
    {
        $this->id = null;
        $this->senderId = null;
        $this->receiverId = null;
        $this->amount = null;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSenderId(): int|null
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getReceiverId(): int|null
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function getAmount(): float|null
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
