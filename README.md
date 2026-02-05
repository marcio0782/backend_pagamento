# Projeto Backend - Slim Framework

Aplicação básica criada com Slim Framework 4.

## Requisitos

- PHP 8.1 ou superior
- Composer

## Instalação

1. Instale as dependências:
```bash
composer install
```

## Executando a aplicação

### Usando o servidor PHP built-in:
```bash
composer start
```
A aplicação estará disponível em: `http://localhost:8000`

### Ou usando o servidor PHP diretamente:
```bash
php -S localhost:8000 -t public
```

## Rotas de Exemplo

- `GET /` - Mensagem de boas-vindas
- `GET /hello/{name}` - Saudação personalizada
- `POST /api/test` - Endpoint de teste para receber dados JSON

## Estrutura do Projeto

```
projeto_backend/
├── public/
│   ├── index.php      # Ponto de entrada da aplicação
│   └── .htaccess      # Configuração do Apache
├── src/               # Código da aplicação (a ser expandido)
├── vendor/            # Dependências do Composer
├── composer.json      # Configuração do Composer
└── README.md          # Este arquivo
```

## Próximos Passos

- Criar controllers na pasta `src/`
- Adicionar middleware personalizado
- Configurar banco de dados
- Implementar autenticação/autorização
- Adicionar validação de dados

