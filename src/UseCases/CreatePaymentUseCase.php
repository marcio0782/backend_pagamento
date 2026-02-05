<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entities\Payment;
use App\Gateways\PaymentRepositoryInterface;

class CreatePaymentUseCase
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function execute(float $amount, string $currency): Payment
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        $currency = strtoupper(trim($currency));
        if (strlen($currency) !== 3) {
            throw new \InvalidArgumentException('Currency must be 3 letters');
        }

        $id = 'pay_' . bin2hex(random_bytes(8));
        $payment = new Payment(
            $id,
            $amount,
            $currency,
            'created',
            (new \DateTimeImmutable())->format('c')
        );

        return $this->paymentRepository->create($payment);
    }
}
