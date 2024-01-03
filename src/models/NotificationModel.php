<?php

declare(strict_types=1);

namespace src\models;

class NotificationModel
{

    private int|null $id;
    private int|null $senderId;
    private int|null $receiverId;
    private string|null $message;
    private string|null $date;

    public function __construct()
    {
        $this->id = null;
        $this->senderId = null;
        $this->receiverId = null;
        $this->message = null;
        $this->date = null;
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

    public function getMessage(): string|null
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getDate(): string|null
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
