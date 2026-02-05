<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Entities\Payment;
use App\Gateways\PaymentRepositoryInterface;

class GetPaymentByIdUseCase
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function execute(string $id): ?Payment
    {
        return $this->paymentRepository->findById($id);
    }
}
