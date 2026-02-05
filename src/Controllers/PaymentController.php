<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Presenters\PaymentPresenter;
use App\UseCases\CreatePaymentUseCase;
use App\UseCases\GetPaymentByIdUseCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PaymentController
{
    private CreatePaymentUseCase $createPaymentUseCase;
    private GetPaymentByIdUseCase $getPaymentByIdUseCase;
    private PaymentPresenter $paymentPresenter;

    public function __construct(
        CreatePaymentUseCase $createPaymentUseCase,
        GetPaymentByIdUseCase $getPaymentByIdUseCase,
        PaymentPresenter $paymentPresenter
    ) {
        $this->createPaymentUseCase = $createPaymentUseCase;
        $this->getPaymentByIdUseCase = $getPaymentByIdUseCase;
        $this->paymentPresenter = $paymentPresenter;
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array) ($request->getParsedBody() ?: []);
        $amount = isset($data['amount']) ? (float) $data['amount'] : 0.0;
        $currency = isset($data['currency']) ? (string) $data['currency'] : '';

        try {
            $payment = $this->createPaymentUseCase->execute($amount, $currency);
        } catch (\InvalidArgumentException $e) {
            return $this->error($response, $e->getMessage(), 422);
        } catch (\Throwable $e) {
            return $this->error($response, 'Internal server error', 500);
        }

        $payload = [
            'status' => 'success',
            'data' => $this->paymentPresenter->present($payment),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $id = isset($args['id']) ? (string) $args['id'] : '';
        if ($id === '') {
            return $this->error($response, 'Payment ID is required', 400);
        }

        try {
            $payment = $this->getPaymentByIdUseCase->execute($id);
        } catch (\Throwable $e) {
            return $this->error($response, 'Internal server error', 500);
        }

        if ($payment === null) {
            return $this->error($response, 'Payment not found', 404);
        }

        $payload = [
            'status' => 'success',
            'data' => $this->paymentPresenter->present($payment),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function error(Response $response, string $message, int $statusCode): Response
    {
        $payload = [
            'status' => 'error',
            'error' => $message,
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}
