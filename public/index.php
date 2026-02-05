<?php

declare(strict_types=1);

use App\Infrastructure\Container;
use App\Infrastructure\Routes;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Criar a aplicação Slim
$app = AppFactory::create();

// Configurar middleware
// CORS deve ser o primeiro middleware para funcionar corretamente
$app->add(new \App\Middleware\CorsMiddleware());
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Configurar Dependency Injection
$container = Container::getInstance();

// Registrar rotas (delegando para a classe Routes)
$routes = new Routes($app, $container);
$routes->register();

// Executar a aplicação
$app->run();

