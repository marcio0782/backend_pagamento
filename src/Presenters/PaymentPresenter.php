<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Entities\Payment;

class PaymentPresenter
{
    public function present(Payment $payment): array
    {
        return $payment->toArray();
    }
}
