<?php

declare(strict_types=1);

namespace App\Entities;

class Payment
{
    private string $id;
    private float $amount;
    private string $currency;
    private string $status;
    private string $createdAt;

    public function __construct(string $id, float $amount, string $currency, string $status, string $createdAt)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
