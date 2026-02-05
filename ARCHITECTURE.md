# Clean Architecture - Estrutura do Projeto

Este projeto segue os princípios da **Clean Architecture**, organizando o código em camadas bem definidas com dependências unidirecionais.

## Estrutura de Pastas

```
src/
├── Entities/          # Enterprise Business Rules (Camada mais interna)
├── UseCases/          # Application Business Rules
├── Controllers/       # Interface Adapters
├── Presenters/        # Interface Adapters
├── Gateways/          # Interface Adapters (Interfaces)
├── Repositories/      # Frameworks & Drivers (Implementações)
├── Infrastructure/    # Frameworks & Drivers (DB, APIs externas, etc.)
└── Models/            # Models (se necessário)
```

## Camadas da Arquitetura

### 1. Entities (Enterprise Business Rules)
- **Localização:** `src/Entities/`
- **Responsabilidade:** Regras de negócio da empresa, independentes de frameworks
- **Exemplo:** `User.php` - Entidade que representa um usuário

### 2. Use Cases (Application Business Rules)
- **Localização:** `src/UseCases/`
- **Responsabilidade:** Lógica de negócio da aplicação, orquestra as entidades
- **Exemplo:** `GetUserByIdUseCase.php` - Caso de uso para buscar usuário por ID

### 3. Interface Adapters
- **Controllers:** `src/Controllers/` - Recebem requisições HTTP e coordenam Use Cases
- **Presenters:** `src/Presenters/` - Formatam dados para apresentação
- **Gateways:** `src/Gateways/` - Interfaces que definem contratos (ex: `UserRepositoryInterface`)

### 4. Frameworks & Drivers
- **Repositories:** `src/Repositories/` - Implementações concretas dos repositórios
- **Infrastructure:** `src/Infrastructure/` - Configurações de banco de dados, APIs externas, etc.

## Fluxo de Dados

```
Request → Controller → Use Case → Repository → Entity
                                    ↓
Response ← Presenter ← Use Case ← Repository
```

## Exemplo de Uso

### Criando uma nova funcionalidade:

1. **Criar a Entity** (`src/Entities/`)
```php
namespace App\Entities;
class Product { ... }
```

2. **Criar o Gateway (Interface)** (`src/Gateways/`)
```php
namespace App\Gateways;
interface ProductRepositoryInterface { ... }
```

3. **Criar o Use Case** (`src/UseCases/`)
```php
namespace App\UseCases;
class GetProductUseCase { ... }
```

4. **Criar o Repository** (`src/Repositories/`)
```php
namespace App\Repositories;
class ProductRepository implements ProductRepositoryInterface { ... }
```

5. **Criar o Presenter** (`src/Presenters/`)
```php
namespace App\Presenters;
class ProductPresenter { ... }
```

6. **Criar o Controller** (`src/Controllers/`)
```php
namespace App\Controllers;
class ProductController { ... }
```

7. **Registrar a rota** (`public/index.php`)
```php
$app->get('/api/products/{id}', [ProductController::class, 'getProduct']);
```

## Princípios

- **Dependency Rule:** Dependências apontam sempre para dentro (camadas internas não dependem de camadas externas)
- **Separation of Concerns:** Cada camada tem uma responsabilidade específica
- **Testabilidade:** Fácil de testar isoladamente cada camada
- **Independência:** Entidades e Use Cases são independentes de frameworks

