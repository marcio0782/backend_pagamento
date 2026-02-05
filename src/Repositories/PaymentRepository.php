<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Payment;
use App\Gateways\PaymentRepositoryInterface;
use PDO;

class PaymentRepository implements PaymentRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(Payment $payment): Payment
    {
        $stmt = $this->db->prepare(
            'INSERT INTO payments (id, amount, currency, status, created_at)
             VALUES (:id, :amount, :currency, :status, :created_at)'
        );

        $stmt->execute([
            'id' => $payment->getId(),
            'amount' => $payment->getAmount(),
            'currency' => $payment->getCurrency(),
            'status' => $payment->getStatus(),
            'created_at' => $payment->getCreatedAt(),
        ]);

        return $payment;
    }

    public function findById(string $id): ?Payment
    {
        $stmt = $this->db->prepare(
            'SELECT id, amount, currency, status, created_at
             FROM payments
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        if (!is_array($row)) {
            return null;
        }

        $createdAt = (string) $row['created_at'];
        try {
            $createdAt = (new \DateTimeImmutable($createdAt))->format('c');
        } catch (\Throwable $e) {
            // Keep original string 
        }

        return new Payment(
            (string) $row['id'],
            (float) $row['amount'],
            (string) $row['currency'],
            (string) $row['status'],
            $createdAt
        );
    }
}
