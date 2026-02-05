<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * Middleware - CORS
 * Permite requisições cross-origin do frontend
 */
class CorsMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Obter origem da requisição
        $origin = $request->getHeaderLine('Origin');
        
        // Lista de origens permitidas (em produção, usar variável de ambiente)
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:3001',
            'http://127.0.0.1:3000',
        ];

        // Se a origem não estiver na lista, usar a primeira permitida como padrão
        $allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : $allowedOrigins[0];

        // Se for uma requisição OPTIONS (preflight), retornar resposta imediatamente
        if ($request->getMethod() === 'OPTIONS') {
            $response = new \Slim\Psr7\Response();
            return $this->addCorsHeaders($response, $allowedOrigin);
        }

        // Processar a requisição normalmente
        $response = $handler->handle($request);

        // Adicionar headers CORS na resposta
        return $this->addCorsHeaders($response, $allowedOrigin);
    }

    private function addCorsHeaders(Response $response, string $origin): Response
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '3600');
    }
}
