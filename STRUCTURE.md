# Estrutura do Projeto - Organização e Delegação

## Arquitetura de Arquivos

```
projeto_backend/
├── public/
│   ├── index.php          # Ponto de entrada - apenas bootstrap
│   └── .htaccess
├── src/
│   ├── Controllers/       # Interface Adapters - Recebem requisições HTTP
│   ├── Entities/          # Enterprise Business Rules
│   ├── UseCases/          # Application Business Rules
│   ├── Presenters/        # Interface Adapters - Formatam respostas
│   ├── Gateways/          # Interface Adapters - Interfaces/Contratos
│   ├── Repositories/      # Frameworks & Drivers - Implementações
│   ├── Infrastructure/    # Frameworks & Drivers - Configurações
│   │   ├── Container.php  # Dependency Injection Container
│   │   └── Routes.php     # Configuração de Rotas
│   └── Models/            # Models (se necessário)
└── vendor/                # Dependências do Composer
```

## Responsabilidades Delegadas

### 1. `public/index.php`
**Responsabilidade:** Apenas bootstrap da aplicação
- Carrega autoloader
- Cria instância do Slim
- Configura middleware básico
- Inicializa Container e Routes
- Executa aplicação

**Código mínimo e limpo:**
```php
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$container = Container::getInstance();
$routes = new Routes($app, $container);
$routes->register();

$app->run();
```

### 2. `src/Infrastructure/Container.php`
**Responsabilidade:** Dependency Injection (DI)
- Centraliza criação de dependências
- Gerencia ciclo de vida dos serviços
- Resolve dependências automaticamente
- Singleton pattern para garantir uma única instância

**Uso:**
```php
$container = Container::getInstance();
$userController = $container->get(UserController::class);
```

### 3. `src/Infrastructure/Routes.php`
**Responsabilidade:** Configuração de Rotas
- Organiza todas as rotas da aplicação
- Separa rotas por funcionalidade
- Usa controllers da Clean Architecture
- Fácil manutenção e expansão

**Estrutura:**
```php
class Routes
{
    public function register(): void
    {
        $this->registerHealthCheck();
        $this->registerExampleRoutes();
        $this->registerUserRoutes();
        // Adicionar mais grupos de rotas aqui
    }
}
```

## Fluxo de Requisição

```
1. Requisição HTTP → public/index.php
2. index.php → Cria App Slim
3. index.php → Inicializa Container (DI)
4. index.php → Registra Routes
5. Routes → Define rotas e associa a Controllers
6. Controller → Usa Use Cases
7. Use Case → Usa Repository (via Interface)
8. Repository → Retorna Entity
9. Use Case → Retorna Entity para Controller
10. Controller → Usa Presenter para formatar
11. Presenter → Retorna Response formatada
12. Response → Cliente recebe JSON
```

## Vantagens desta Estrutura

### ✅ Separação de Responsabilidades
- Cada classe tem uma responsabilidade única
- Fácil de entender e manter

### ✅ Testabilidade
- Fácil criar mocks para testes
- Dependências injetadas facilitam testes unitários

### ✅ Escalabilidade
- Adicionar novas rotas: apenas editar `Routes.php`
- Adicionar novos serviços: apenas editar `Container.php`
- Seguir padrão Clean Architecture

### ✅ Manutenibilidade
- Código organizado e bem estruturado
- Fácil localizar onde fazer mudanças
- Reduz acoplamento entre componentes

## Como Adicionar Nova Funcionalidade

### Exemplo: Adicionar Product

1. **Criar Entity:**
```php
// src/Entities/Product.php
class Product { ... }
```

2. **Criar Gateway (Interface):**
```php
// src/Gateways/ProductRepositoryInterface.php
interface ProductRepositoryInterface { ... }
```

3. **Criar Repository:**
```php
// src/Repositories/ProductRepository.php
class ProductRepository implements ProductRepositoryInterface { ... }
```

4. **Criar Use Case:**
```php
// src/UseCases/GetProductUseCase.php
class GetProductUseCase { ... }
```

5. **Criar Presenter:**
```php
// src/Presenters/ProductPresenter.php
class ProductPresenter { ... }
```

6. **Criar Controller:**
```php
// src/Controllers/ProductController.php
class ProductController { ... }
```

7. **Registrar no Container:**
```php
// src/Infrastructure/Container.php
$this->services[ProductRepositoryInterface::class] = function () {
    return new ProductRepository();
};
// ... registrar outros serviços
```

8. **Registrar Rotas:**
```php
// src/Infrastructure/Routes.php
private function registerProductRoutes(): void
{
    $productController = $this->container->get(ProductController::class);
    $this->app->get('/api/products/{id}', 
        [$productController, 'getProduct']
    );
}
```

## Boas Práticas

1. **Sempre use interfaces** para repositories (Gateways)
2. **Injete dependências** via construtor
3. **Use o Container** para resolver dependências
4. **Mantenha index.php limpo** - apenas bootstrap
5. **Organize rotas** por funcionalidade em Routes.php
6. **Siga Clean Architecture** - dependências apontam para dentro



