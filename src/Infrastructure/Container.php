<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Controllers\PaymentController;
use App\Gateways\PaymentRepositoryInterface;
use App\Presenters\PaymentPresenter;
use App\Repositories\PaymentRepository;
use App\UseCases\CreatePaymentUseCase;
use App\UseCases\GetPaymentByIdUseCase;
use PDO;

/**
 * Container - Dependency Injection
 * Centraliza a criacao e configuracao de dependencias
 */
class Container
{
    private static ?self $instance = null;
    /** @var array<string, callable|object> */
    private array $services = [];

    private function __construct()
    {
        $this->registerServices();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Registra todos os servicos e dependencias
     */
    private function registerServices(): void
    {
        // Infra
        $this->services[PDO::class] = function (): PDO {
            return Database::connect();
        };

        // Repositories
        $this->services[PaymentRepositoryInterface::class] = function (): PaymentRepositoryInterface {
            return new PaymentRepository($this->get(PDO::class));
        };

        // Use Cases
        $this->services[CreatePaymentUseCase::class] = function (): CreatePaymentUseCase {
            return new CreatePaymentUseCase(
                $this->get(PaymentRepositoryInterface::class)
            );
        };

        $this->services[GetPaymentByIdUseCase::class] = function (): GetPaymentByIdUseCase {
            return new GetPaymentByIdUseCase(
                $this->get(PaymentRepositoryInterface::class)
            );
        };

        // Presenters
        $this->services[PaymentPresenter::class] = function (): PaymentPresenter {
            return new PaymentPresenter();
        };

        // Controllers
        $this->services[PaymentController::class] = function (): PaymentController {
            return new PaymentController(
                $this->get(CreatePaymentUseCase::class),
                $this->get(GetPaymentByIdUseCase::class),
                $this->get(PaymentPresenter::class)
            );
        };
    }

    /**
     * Obtem um servico do container
     */
    public function get(string $id): object
    {
        if (!isset($this->services[$id])) {
            throw new \RuntimeException("Service {$id} not found");
        }

        $service = $this->services[$id];
        if (is_callable($service)) {
            $service = $service();
            $this->services[$id] = $service;
        }

        if (!is_object($service)) {
            throw new \RuntimeException("Service {$id} did not resolve to an object");
        }

        return $service;
    }

    /**
     * Registra um servico customizado
     */
    public function set(string $id, callable|object $service): void
    {
        $this->services[$id] = $service;
    }
}
