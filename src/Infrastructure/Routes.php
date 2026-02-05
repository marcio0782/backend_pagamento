<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Controllers\PaymentController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

/**
 * Routes - Configuracao de Rotas
 * Centraliza todas as definicoes de rotas da aplicacao
 */
class Routes
{
    private App $app;
    private Container $container;

    public function __construct(App $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
    }

    /**
     * Registra todas as rotas da aplicacao
     */
    public function register(): void
    {
        $this->registerHealthCheck();
        $this->registerPaymentRoutes();
    }

    /**
     * Rotas de health check e status
     */
    private function registerHealthCheck(): void
    {
        $this->app->get('/', function (Request $request, Response $response): Response {
            $response->getBody()->write(json_encode([
                'message' => 'Backend de Pagamentos',
                'status' => 'success',
                'version' => '1.0.0'
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        });
    }

    /**
     * Rotas de pagamento
     */
    private function registerPaymentRoutes(): void
    {
        $controller = $this->container->get(PaymentController::class);
        if (!$controller instanceof PaymentController) {
            throw new \RuntimeException('PaymentController service not found');
        }

        // POST /api/payments - Criar pagamento
        $this->app->post('/api/payments', [$controller, 'create']);

        // GET /api/payments/{id} - Buscar pagamento por ID
        $this->app->get('/api/payments/{id}', [$controller, 'getById']);
    }
}
