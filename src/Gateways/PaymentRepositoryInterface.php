<?php

declare(strict_types=1);

namespace App\Gateways;

use App\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function create(Payment $payment): Payment;
    public function findById(string $id): ?Payment;
}
