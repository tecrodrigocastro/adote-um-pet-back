# Auth Module

Módulo de autenticação isolado seguindo arquitetura modular Laravel.

## Estrutura

```
app/Modules/Auth/
├── Application/          # Camada de aplicação
│   ├── Actions/         # Actions (use cases)
│   ├── Casts/           # Custom Casts para Value Objects
│   └── DTOs/            # Data Transfer Objects
├── Domain/              # Camada de domínio
│   ├── Exceptions/      # Exceções de domínio
│   └── ValueObjects/    # Value Objects (Email, Password, Cnpj, Phone, ZipCode)
├── Infrastructure/      # Camada de infraestrutura
│   ├── Casts/           # Eloquent Casts para Value Objects
│   ├── Http/
│   │   ├── Controllers/ # HTTP Controllers
│   │   └── Requests/    # Form Requests
│   └── Routes/          # Arquivos de rotas
└── Providers/           # Service Providers
```

## Endpoints

- `POST /api/auth/register` - Cadastro de usuário
- `POST /api/auth/register/organization` - Cadastro de organização
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout (autenticado)
- `POST /api/auth/refresh` - Refresh token (autenticado)
- `GET /api/auth/me` - Dados do usuário autenticado

## Autenticação

Utiliza Laravel Sanctum para autenticação via tokens.

## DTOs

O módulo usa [Spatie Laravel Data](https://spatie.be/docs/laravel-data) v4.23 para DTOs com integração de Value Objects via Custom Casts.

## Value Objects

Value Objects implementados:
- `Email` - Validação e normalização de email
- `Password` - Validação de senha (mínimo 6 caracteres)
- `Cnpj` - Validação e formatação de CNPJ
- `Phone` - Validação de telefone (10 ou 11 dígitos)
- `ZipCode` - Validação de CEP (8 dígitos)

## Testes

Execute testes do módulo:

```bash
php artisan test --filter Auth
```

Total: 107 testes (75 deprecated + 32 passed)
