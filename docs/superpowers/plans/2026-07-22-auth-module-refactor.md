# Refatoração Auth para Módulo Isolado Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refatorar sistema de autenticação atual para módulo isolado seguindo arquitetura modular Laravel com Domain-Driven Design

**Architecture:** Estrutura modular em `app/Modules/Auth/` com camadas Application (DTOs, Actions), Domain (Exceptions), Infrastructure (HTTP, Routes) e Providers. Mantém compatibilidade total com código existente através de namespace updates.

**Tech Stack:** Laravel 11.x, PHP 8.5, Laravel Sanctum, PHPUnit

## Global Constraints

- PHP >= 8.5
- Laravel >= 11.x
- Preservar todos testes existentes
- Manter 100% compatibilidade com endpoints existentes
- Não quebrar integrações existentes (User model, Organization model)
- Seguir PSR-4 autoloading
- Usar `declare(strict_types=1)` em TODOS arquivos PHP
- Tipagem forte OBRIGATÓRIA:
  - Tipos de retorno em todos métodos e funções
  - Tipos de parâmetros em todos métodos e funções
  - Tipos de propriedades em todas classes (readonly quando aplicável)
  - Sem mixed, usar union types quando necessário
  - DTOs com propriedades readonly e construtor tipado

---

### Task 1: Instalar Spatie Laravel Data

**Files:**
- Modify: `composer.json`
- Modify: `composer.lock`

**Interfaces:**
- Consumes: nenhum (task inicial)
- Produces: pacote spatie/laravel-data instalado

- [ ] **Step 1: Verificar se pacote já está instalado**

Run: `composer show spatie/laravel-data 2>&1`
Expected: package info ou "not found"

- [ ] **Step 2: Instalar spatie/laravel-data se necessário**

```bash
composer require spatie/laravel-data
```

Expected: Package installed successfully

- [ ] **Step 3: Publicar configuração (opcional)**

```bash
php artisan vendor:publish --tag=data-config
```

Expected: config/data.php published

- [ ] **Step 4: Commit instalação**

```bash
git add composer.json composer.lock config/data.php
git commit -m "feat(deps): install spatie/laravel-data for DTOs"
```

---

### Task 2: Criar Estrutura de Diretórios do Módulo

**Files:**
- Create: `app/Modules/Auth/Application/Actions/.gitkeep`
- Create: `app/Modules/Auth/Application/DTOs/.gitkeep`
- Create: `app/Modules/Auth/Domain/Exceptions/.gitkeep`
- Create: `app/Modules/Auth/Domain/ValueObjects/.gitkeep`
- Create: `app/Modules/Auth/Infrastructure/Http/Controllers/.gitkeep`
- Create: `app/Modules/Auth/Infrastructure/Http/Requests/.gitkeep`
- Create: `app/Modules/Auth/Infrastructure/Routes/.gitkeep`
- Create: `app/Modules/Auth/Providers/.gitkeep`
- Create: `app/Modules/Auth/Tests/Unit/.gitkeep`
- Create: `app/Modules/Auth/Tests/Feature/.gitkeep`

**Interfaces:**
- Consumes: nenhum (task inicial)
- Produces: estrutura de diretórios em `app/Modules/Auth/` incluindo ValueObjects

- [ ] **Step 1: Criar estrutura completa de diretórios**

```bash
mkdir -p app/Modules/Auth/Application/{Actions,DTOs}
mkdir -p app/Modules/Auth/Domain/{Exceptions,ValueObjects}
mkdir -p app/Modules/Auth/Infrastructure/Http/{Controllers,Requests}
mkdir -p app/Modules/Auth/Infrastructure/Routes
mkdir -p app/Modules/Auth/Providers
mkdir -p app/Modules/Auth/Tests/{Unit,Feature}
```

- [ ] **Step 2: Criar arquivos .gitkeep para preservar estrutura**

```bash
touch app/Modules/Auth/Application/Actions/.gitkeep
touch app/Modules/Auth/Application/DTOs/.gitkeep
touch app/Modules/Auth/Domain/Exceptions/.gitkeep
touch app/Modules/Auth/Domain/ValueObjects/.gitkeep
touch app/Modules/Auth/Infrastructure/Http/Controllers/.gitkeep
touch app/Modules/Auth/Infrastructure/Http/Requests/.gitkeep
touch app/Modules/Auth/Infrastructure/Routes/.gitkeep
touch app/Modules/Auth/Providers/.gitkeep
touch app/Modules/Auth/Tests/Unit/.gitkeep
touch app/Modules/Auth/Tests/Feature/.gitkeep
```

- [ ] **Step 3: Verificar estrutura criada**

Run: `tree app/Modules/Auth -L 3`
Expected: estrutura de diretórios exibida corretamente

- [ ] **Step 4: Commit estrutura base**

```bash
git add app/Modules/Auth
git commit -m "feat(auth): create auth module directory structure
"
```

---

### Task 3: Mover Domain Exceptions

**Files:**
- Create: `app/Modules/Auth/Domain/Exceptions/InvalidCredentialsException.php`
- Create: `app/Modules/Auth/Domain/Exceptions/EmailAlreadyRegisteredException.php`
- Create: `app/Modules/Auth/Domain/Exceptions/CnpjAlreadyRegisteredException.php`
- Create: `app/Modules/Auth/Domain/Exceptions/NoActiveTokenException.php`

**Interfaces:**
- Consumes: estrutura de diretórios de Task 2
- Produces: exceptions em namespace `App\Modules\Auth\Domain\Exceptions\`

- [ ] **Step 1: Copiar InvalidCredentialsException com namespace atualizado e tipagem forte**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class InvalidCredentialsException extends Exception
{
    protected $message = 'Credenciais inválidas';

    protected $code = 401;
}
```

File: `app/Modules/Auth/Domain/Exceptions/InvalidCredentialsException.php`

IMPORTANTE: Adicionar `declare(strict_types=1)` e garantir que todas properties mantenham tipagem quando aplicável.

- [ ] **Step 2: Copiar EmailAlreadyRegisteredException com namespace atualizado**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class EmailAlreadyRegisteredException extends Exception
{
    protected $message = 'Email já cadastrado';

    protected $code = 409;
}
```

File: `app/Modules/Auth/Domain/Exceptions/EmailAlreadyRegisteredException.php`

- [ ] **Step 3: Copiar CnpjAlreadyRegisteredException com namespace atualizado**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class CnpjAlreadyRegisteredException extends Exception
{
    protected $message = 'CNPJ já cadastrado';

    protected $code = 409;
}
```

File: `app/Modules/Auth/Domain/Exceptions/CnpjAlreadyRegisteredException.php`

- [ ] **Step 4: Copiar NoActiveTokenException com namespace atualizado**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class NoActiveTokenException extends Exception
{
    protected $message = 'Nenhum token ativo encontrado';

    protected $code = 401;
}
```

File: `app/Modules/Auth/Domain/Exceptions/NoActiveTokenException.php`

- [ ] **Step 5: Verificar sintaxe dos arquivos**

Run: `php -l app/Modules/Auth/Domain/Exceptions/*.php`
Expected: No syntax errors detected

- [ ] **Step 6: Commit exceptions**

```bash
git add app/Modules/Auth/Domain/Exceptions
git commit -m "feat(auth): move domain exceptions to auth module
"
```

---

### Task 4: Criar Domain Value Objects

**Files:**
- Create: `app/Modules/Auth/Domain/ValueObjects/Email.php`
- Create: `app/Modules/Auth/Domain/ValueObjects/Password.php`
- Create: `app/Modules/Auth/Domain/ValueObjects/Cnpj.php`

**Interfaces:**
- Consumes: estrutura de diretórios de Task 2
- Produces: Value Objects em namespace `App\Modules\Auth\Domain\ValueObjects\`

**CRÍTICO - Value Objects:**
- TODOS Value Objects devem ser `final class`
- Propriedades `private readonly` com tipagem forte
- Validação no construtor (throw exception se inválido)
- Imutáveis (sem setters)
- Método `value(): string` para obter valor primitivo
- Método `equals(self $other): bool` para comparação
- Implementar `Stringable` interface
- Exemplo completo:

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Email implements Stringable
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $this->validate($value);
    }

    private function validate(string $email): string
    {
        $email = trim(strtolower($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address: {$email}");
        }

        return $email;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
```

- [ ] **Step 1: Criar Value Object Email**

Criar `app/Modules/Auth/Domain/ValueObjects/Email.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Domain\ValueObjects`
- `final class` + `implements Stringable`
- Validação email no construtor
- Métodos: `value()`, `equals()`, `__toString()`

- [ ] **Step 2: Criar Value Object Password**

Criar `app/Modules/Auth/Domain/ValueObjects/Password.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Domain\ValueObjects`
- `final class` + `implements Stringable`
- Validação mínimo 6 caracteres no construtor
- Métodos: `value()`, `equals()`, `__toString()`
- IMPORTANTE: NÃO hash aqui - Value Object armazena plain text, hash é responsabilidade da Action

- [ ] **Step 3: Criar Value Object Cnpj**

Criar `app/Modules/Auth/Domain/ValueObjects/Cnpj.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Domain\ValueObjects`
- `final class` + `implements Stringable`
- Validação formato CNPJ no construtor (regex + dígitos verificadores)
- Normalização: remover pontuação, manter só números
- Métodos: `value()`, `equals()`, `__toString()`, `formatted(): string` (retorna com máscara)

- [ ] **Step 4: Verificar sintaxe de todos Value Objects**

Run: `php -l app/Modules/Auth/Domain/ValueObjects/*.php`
Expected: No syntax errors detected

- [ ] **Step 5: Commit Value Objects**

```bash
git add app/Modules/Auth/Domain/ValueObjects
git commit -m "feat(auth): create domain value objects (Email, Password, Cnpj)"
```

---

### Task 5: Mover Application DTOs usando Spatie Laravel Data

**Files:**
- Create: `app/Modules/Auth/Application/DTOs/LoginUserDto.php`
- Create: `app/Modules/Auth/Application/DTOs/RegisterUserDto.php`
- Create: `app/Modules/Auth/Application/DTOs/RegisterOrganizationDto.php`
- Create: `app/Modules/Auth/Application/DTOs/AddressDto.php`
- Create: `app/Modules/Auth/Application/DTOs/SocialMediaDto.php`

**Interfaces:**
- Consumes: estrutura de diretórios de Task 2, pacote spatie/laravel-data de Task 1, Value Objects de Task 4 (opcional)
- Produces: DTOs em namespace `App\Modules\Auth\Application\DTOs\` usando Spatie Data

**CRÍTICO - DTOs com Spatie Laravel Data + Value Objects:**
- TODOS DTOs devem `extends Data` de `Spatie\LaravelData\Data`
- Usar promoted constructor properties: `public function __construct(public readonly string $email)`
- Tipagem forte OBRIGATÓRIA em todas properties
- OPCIONAL: Usar Value Objects onde faz sentido (Email, Password, Cnpj) ao invés de strings primitivas
- Usar attributes do Spatie quando necessário: `#[MapFrom('user_email')]`, `#[Validation('required|email')]`, etc
- Remover método `fromArray()` - Spatie Data provê `from()` automaticamente
- Spatie Data suporta casts automáticos para Value Objects se implementarem `Castable` ou usar `#[WithCast]`
- Exemplo completo (com string primitiva):
```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use Spatie\LaravelData\Data;

final class LoginUserDto extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
```

- [ ] **Step 1: Ler DTO LoginUserDto original**

Run: Read tool em `app/Dtos/Auth/LoginUserDto.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 2: Criar LoginUserDto usando Spatie Data**

Criar `app/Modules/Auth/Application/DTOs/LoginUserDto.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Application\DTOs`
- `extends Data` de `Spatie\LaravelData\Data`
- Promoted constructor properties com readonly
- DECISÃO: usar `string $email` e `string $password` OU `Email $email` e `Password $password` (Value Objects)
- Se usar Value Objects, adicionar imports: `use App\Modules\Auth\Domain\ValueObjects\{Email, Password}`
- Manter métodos auxiliares existentes (ex: `credentials()`)
- Remover `fromArray()` - Spatie provê `from()` automaticamente

- [ ] **Step 3: Ler DTO RegisterUserDto original**

Run: Read tool em `app/Dtos/Auth/RegisterUserDto.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 4: Criar RegisterUserDto usando Spatie Data**

Criar `app/Modules/Auth/Application/DTOs/RegisterUserDto.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Application\DTOs`
- `extends Data`
- Promoted constructor properties com readonly e tipagem forte
- Converter arrays/objects aninhados para usar DataCollection se aplicável
- Remover `fromArray()` - usar `from()` do Spatie

- [ ] **Step 5: Ler DTO RegisterOrganizationDto original**

Run: Read tool em `app/Dtos/Auth/RegisterOrganizationDto.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 6: Criar RegisterOrganizationDto usando Spatie Data**

Criar `app/Modules/Auth/Application/DTOs/RegisterOrganizationDto.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Application\DTOs`
- `extends Data`
- Promoted constructor properties com readonly
- Se usar AddressDto ou SocialMediaDto nested, tipar como: `public readonly AddressDto $address`
- Imports: `use App\Modules\Auth\Application\DTOs\{AddressDto, SocialMediaDto}`
- Remover `fromArray()` - Spatie auto-casta nested DTOs

- [ ] **Step 7: Ler DTO AddressDto original**

Run: Read tool em `app/Dtos/Auth/AddressDto.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 8: Criar AddressDto usando Spatie Data**

Criar `app/Modules/Auth/Application/DTOs/AddressDto.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Application\DTOs`
- `extends Data`
- Promoted constructor properties com readonly
- Remover `fromArray()`

- [ ] **Step 9: Ler DTO SocialMediaDto original**

Run: Read tool em `app/Dtos/Auth/SocialMediaDto.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 10: Criar SocialMediaDto usando Spatie Data**

Criar `app/Modules/Auth/Application/DTOs/SocialMediaDto.php` com:
- `declare(strict_types=1)`
- Namespace: `App\Modules\Auth\Application\DTOs`
- `extends Data`
- Promoted constructor properties com readonly (ex: `public readonly ?string $instagram`)
- Usar nullable types onde aplicável
- Remover `fromArray()`

- [ ] **Step 11: Verificar sintaxe de todos DTOs**

Run: `php -l app/Modules/Auth/Application/DTOs/*.php`
Expected: No syntax errors detected

- [ ] **Step 12: Commit DTOs**

```bash
git add app/Modules/Auth/Application/DTOs
git commit -m "feat(auth): move application DTOs to auth module
"
```

---

### Task 6: Mover Application Actions

**Files:**
- Create: `app/Modules/Auth/Application/Actions/LoginUserAction.php`
- Create: `app/Modules/Auth/Application/Actions/LoginUserResult.php`
- Create: `app/Modules/Auth/Application/Actions/LogoutUserAction.php`
- Create: `app/Modules/Auth/Application/Actions/RefreshUserTokenAction.php`
- Create: `app/Modules/Auth/Application/Actions/RegisterUserAction.php`
- Create: `app/Modules/Auth/Application/Actions/RegisterOrganizationAction.php`

**Interfaces:**
- Consumes: DTOs de Task 5 (Spatie Data), Exceptions de Task 3, Value Objects de Task 4 (opcional via DTOs)
- Produces: Actions em namespace `App\Modules\Auth\Application\Actions\`

**CRÍTICO - Tipagem Forte em Actions:**
- Método `handle()` com tipo de retorno explícito
- Parâmetros do construtor e métodos tipados
- Sem tipos nullables desnecessários
- LoginUserResult com propriedades readonly tipadas
- Exemplo: `public function handle(LoginUserDto $dto): LoginUserResult`

- [ ] **Step 1: Ler LoginUserAction original**

Run: Read tool em `app/Actions/Auth/LoginUserAction.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 2: Copiar LoginUserAction com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/LoginUserAction.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Application\Actions;
```

Imports:
```php
use App\Modules\Auth\Application\DTOs\LoginUserDto;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 3: Ler LoginUserResult original**

Run: Read tool em `app/Actions/Auth/LoginUserResult.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 4: Copiar LoginUserResult com namespace atualizado**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/LoginUserResult.php` atualizando namespace para:

```php
namespace App\Modules\Auth\Application\Actions;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.
Import User model se necessário.

- [ ] **Step 5: Ler LogoutUserAction original**

Run: Read tool em `app/Actions/Auth/LogoutUserAction.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 6: Copiar LogoutUserAction com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/LogoutUserAction.php` atualizando namespace e imports conforme necessário.

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 7: Ler RefreshUserTokenAction original**

Run: Read tool em `app/Actions/Auth/RefreshUserTokenAction.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 8: Copiar RefreshUserTokenAction com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/RefreshUserTokenAction.php` atualizando namespace e imports (incluindo NoActiveTokenException se usado).

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 9: Ler RegisterUserAction original**

Run: Read tool em `app/Actions/Auth/RegisterUserAction.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 10: Copiar RegisterUserAction com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/RegisterUserAction.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Application\Actions;
```

Imports (ajustar conforme necessário):
```php
use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use App\Models\User;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 11: Ler RegisterOrganizationAction original**

Run: Read tool em `app/Actions/Auth/RegisterOrganizationAction.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 12: Copiar RegisterOrganizationAction com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Application/Actions/RegisterOrganizationAction.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Application\Actions;
```

Imports (ajustar conforme necessário):
```php
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use App\Modules\Auth\Domain\Exceptions\CnpjAlreadyRegisteredException;
use App\Models\Organization;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 13: Verificar sintaxe de todas Actions**

Run: `php -l app/Modules/Auth/Application/Actions/*.php`
Expected: No syntax errors detected

- [ ] **Step 14: Commit Actions**

```bash
git add app/Modules/Auth/Application/Actions
git commit -m "feat(auth): move application actions to auth module
"
```

---

### Task 7: Mover Infrastructure HTTP Requests

**Files:**
- Create: `app/Modules/Auth/Infrastructure/Http/Requests/LoginRequest.php`
- Create: `app/Modules/Auth/Infrastructure/Http/Requests/RegisterRequest.php`
- Create: `app/Modules/Auth/Infrastructure/Http/Requests/RegisterOrganizationRequest.php`

**Interfaces:**
- Consumes: DTOs de Task 5 (Spatie Data DTOs)
- Produces: Form Requests em namespace `App\Modules\Auth\Infrastructure\Http\Requests\`

**CRÍTICO - Tipagem Forte em Requests + Spatie Data:**
- Métodos `authorize()`, `rules()`, `messages()` com tipos de retorno explícitos (`: bool`, `: array`)
- Método `toDto()` com tipo de retorno do DTO específico
- Usar `DTO::from($this->validated())` ao invés de `DTO::fromArray()`
- Exemplo:
```php
public function toDto(): LoginUserDto
{
    return LoginUserDto::from($this->validated());
}
```

- [ ] **Step 1: Ler LoginRequest original**

Run: Read tool em `app/Http/Requests/LoginRequest.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 2: Copiar LoginRequest com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Infrastructure/Http/Requests/LoginRequest.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Infrastructure\Http\Requests;
```

Imports:
```php
use App\Modules\Auth\Application\DTOs\LoginUserDto;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 3: Ler RegisterRequest original**

Run: Read tool em `app/Http/Requests/RegisterRequest.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 4: Copiar RegisterRequest com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Infrastructure/Http/Requests/RegisterRequest.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Infrastructure\Http\Requests;
```

Imports:
```php
use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 5: Ler RegisterOrganizationRequest original**

Run: Read tool em `app/Http/Requests/RegisterOrganizationRequest.php`
Expected: conteúdo completo do arquivo

- [ ] **Step 6: Copiar RegisterOrganizationRequest com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Infrastructure/Http/Requests/RegisterOrganizationRequest.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Infrastructure\Http\Requests;
```

Imports:
```php
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

- [ ] **Step 7: Verificar sintaxe de todos Requests**

Run: `php -l app/Modules/Auth/Infrastructure/Http/Requests/*.php`
Expected: No syntax errors detected

- [ ] **Step 8: Commit Requests**

```bash
git add app/Modules/Auth/Infrastructure/Http/Requests
git commit -m "feat(auth): move HTTP requests to auth module infrastructure
"
```

---

### Task 8: Mover Infrastructure HTTP Controller

**Files:**
- Create: `app/Modules/Auth/Infrastructure/Http/Controllers/AuthController.php`

**Interfaces:**
- Consumes: Actions de Task 6, Requests de Task 7, DTOs de Task 5 (indiretamente via Requests)
- Produces: Controller em namespace `App\Modules\Auth\Infrastructure\Http\Controllers\`

**CRÍTICO - Tipagem Forte em Controller:**
- Todos métodos públicos com tipo de retorno `: JsonResponse`
- Parâmetros tipados (FormRequest, Action classes)
- Dependency injection tipada no construtor se houver

- [ ] **Step 1: Ler AuthController original**

Run: Read tool em `app/Http/Controllers/Auth/AuthController.php`
Expected: conteúdo completo do arquivo (273 linhas)

- [ ] **Step 2: Copiar AuthController com namespace e imports atualizados**

Copiar conteúdo para `app/Modules/Auth/Infrastructure/Http/Controllers/AuthController.php` atualizando:

Namespace:
```php
namespace App\Modules\Auth\Infrastructure\Http\Controllers;
```

Imports:
```php
use App\Modules\Auth\Application\Actions\LoginUserAction;
use App\Modules\Auth\Application\Actions\LogoutUserAction;
use App\Modules\Auth\Application\Actions\RefreshUserTokenAction;
use App\Modules\Auth\Application\Actions\RegisterOrganizationAction;
use App\Modules\Auth\Application\Actions\RegisterUserAction;
use App\Modules\Auth\Infrastructure\Http\Requests\LoginRequest;
use App\Modules\Auth\Infrastructure\Http\Requests\RegisterOrganizationRequest;
use App\Modules\Auth\Infrastructure\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
```

Adicionar `declare(strict_types=1);` após tag PHP de abertura.

IMPORTANTE: Resources (UserResource, OrganizationResource) permanecem em `App\Http\Resources\` pois são compartilhados.

- [ ] **Step 3: Verificar sintaxe do Controller**

Run: `php -l app/Modules/Auth/Infrastructure/Http/Controllers/AuthController.php`
Expected: No syntax errors detected

- [ ] **Step 4: Commit Controller**

```bash
git add app/Modules/Auth/Infrastructure/Http/Controllers
git commit -m "feat(auth): move auth controller to auth module infrastructure
"
```

---

### Task 9: Criar Arquivo de Rotas Isolado

**Files:**
- Create: `app/Modules/Auth/Infrastructure/Routes/auth.php`

**Interfaces:**
- Consumes: Controller de Task 8 (`App\Modules\Auth\Infrastructure\Http\Controllers\AuthController`)
- Produces: arquivo de rotas `auth.php` no módulo

- [ ] **Step 1: Criar arquivo auth.php com rotas do módulo**

```php
<?php

declare(strict_types=1);

use App\Modules\Auth\Infrastructure\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
```

File: `app/Modules/Auth/Infrastructure/Routes/auth.php`

- [ ] **Step 2: Verificar sintaxe do arquivo de rotas**

Run: `php -l app/Modules/Auth/Infrastructure/Routes/auth.php`
Expected: No syntax errors detected

- [ ] **Step 3: Commit arquivo de rotas**

```bash
git add app/Modules/Auth/Infrastructure/Routes/auth.php
git commit -m "feat(auth): create isolated route file for auth module
"
```

---

### Task 10: Criar Service Provider do Módulo

**Files:**
- Create: `app/Modules/Auth/Providers/AuthModuleServiceProvider.php`

**Interfaces:**
- Consumes: arquivo de rotas de Task 9 (`app/Modules/Auth/Infrastructure/Routes/auth.php`)
- Produces: Service Provider em `App\Modules\Auth\Providers\AuthModuleServiceProvider`

- [ ] **Step 1: Criar AuthModuleServiceProvider com tipagem forte**

```php
<?php

declare(strict_types=1);

namespace App\Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;

final class AuthModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(
            app_path('Modules/Auth/Infrastructure/Routes/auth.php')
        );
    }
}
```

File: `app/Modules/Auth/Providers/AuthModuleServiceProvider.php`

IMPORTANTE: Métodos `register()` e `boot()` com tipo de retorno `: void` explícito.

- [ ] **Step 2: Verificar sintaxe do Service Provider**

Run: `php -l app/Modules/Auth/Providers/AuthModuleServiceProvider.php`
Expected: No syntax errors detected

- [ ] **Step 3: Commit Service Provider**

```bash
git add app/Modules/Auth/Providers/AuthModuleServiceProvider.php
git commit -m "feat(auth): create auth module service provider
"
```

---

### Task 11: Registrar Módulo no Bootstrap

**Files:**
- Modify: `bootstrap/providers.php`

**Interfaces:**
- Consumes: Service Provider de Task 10 (`App\Modules\Auth\Providers\AuthModuleServiceProvider`)
- Produces: módulo registrado no bootstrap da aplicação

- [ ] **Step 1: Ler arquivo bootstrap/providers.php**

Run: Read tool em `bootstrap/providers.php`
Expected: conteúdo atual do arquivo

- [ ] **Step 2: Adicionar AuthModuleServiceProvider ao array de providers**

Adicionar ao array de providers:

```php
App\Modules\Auth\Providers\AuthModuleServiceProvider::class,
```

Localização: dentro do array retornado, após `AppServiceProvider::class`.

- [ ] **Step 3: Verificar sintaxe do arquivo**

Run: `php -l bootstrap/providers.php`
Expected: No syntax errors detected

- [ ] **Step 4: Limpar cache de configuração**

Run: `php artisan config:clear`
Expected: Configuration cache cleared successfully.

- [ ] **Step 5: Verificar rotas carregadas**

Run: `php artisan route:list | grep auth`
Expected: rotas de auth exibidas com controller do módulo

- [ ] **Step 6: Commit registro do módulo**

```bash
git add bootstrap/providers.php
git commit -m "feat(auth): register auth module service provider
"
```

---

### Task 12: Atualizar Rotas API para Usar Módulo

**Files:**
- Modify: `routes/api.php`

**Interfaces:**
- Consumes: rotas carregadas pelo Service Provider de Task 10
- Produces: routes/api.php sem duplicação de rotas de auth

- [ ] **Step 1: Ler arquivo routes/api.php**

Run: Read tool em `routes/api.php`
Expected: conteúdo atual do arquivo

- [ ] **Step 2: Remover rotas de auth de routes/api.php**

Remover linhas 5 (import AuthController) e 23-33 (grupo de rotas auth):

```php
use App\Http\Controllers\Auth\AuthController;  // <- REMOVER

Route::prefix('auth')->group(function () {     // <- REMOVER linhas 23-33
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
```

- [ ] **Step 3: Verificar sintaxe do arquivo**

Run: `php -l routes/api.php`
Expected: No syntax errors detected

- [ ] **Step 4: Verificar rotas carregadas**

Run: `php artisan route:list | grep auth`
Expected: rotas de auth ainda presentes (carregadas pelo Service Provider)

- [ ] **Step 5: Commit remoção de rotas antigas**

```bash
git add routes/api.php
git commit -m "refactor(auth): remove auth routes from api.php (now loaded by module)
"
```

---

### Task 13: Executar Testes para Validar Refatoração

**Files:**
- Modify: nenhum (apenas execução de testes)

**Interfaces:**
- Consumes: todo código refatorado das Tasks anteriores
- Produces: confirmação de que nada quebrou

- [ ] **Step 1: Limpar todos caches**

Run: `php artisan optimize:clear`
Expected: Caches cleared successfully.

- [ ] **Step 2: Executar testes relacionados a auth**

Run: `php artisan test --filter Auth`
Expected: todos testes passando

- [ ] **Step 3: Se testes falharem, identificar imports quebrados**

Run: Read dos testes que falharam
Expected: identificar classes usando namespaces antigos

- [ ] **Step 4: Atualizar imports em testes que falharam**

Se testes estiverem usando:
- `App\Dtos\Auth\*` → atualizar para `App\Modules\Auth\Application\DTOs\*`
- `App\Actions\Auth\*` → atualizar para `App\Modules\Auth\Application\Actions\*`
- `App\Exceptions\Auth\*` → atualizar para `App\Modules\Auth\Domain\Exceptions\*`

- [ ] **Step 5: Executar todos testes da aplicação**

Run: `php artisan test`
Expected: todos testes passando

- [ ] **Step 6: Se testes passaram, commit confirmação**

```bash
git add tests/
git commit -m "test(auth): update test imports to use auth module namespaces
"
```

---

### Task 14: Remover Arquivos Antigos

**Files:**
- Delete: `app/Http/Controllers/Auth/AuthController.php`
- Delete: `app/Actions/Auth/*`
- Delete: `app/Dtos/Auth/*`
- Delete: `app/Exceptions/Auth/*`
- Delete: `app/Http/Requests/LoginRequest.php`
- Delete: `app/Http/Requests/RegisterRequest.php`
- Delete: `app/Http/Requests/RegisterOrganizationRequest.php`

**Interfaces:**
- Consumes: testes passando de Task 13
- Produces: código antigo removido

- [ ] **Step 1: Executar busca por imports antigos**

Run: `grep -r "App\\\Actions\\\Auth" app/ --include="*.php" | wc -l`
Expected: 0 (nenhum import antigo encontrado)

Run: `grep -r "App\\\Dtos\\\Auth" app/ --include="*.php" | wc -l`
Expected: 0 (nenhum import antigo encontrado)

Run: `grep -r "App\\\Exceptions\\\Auth" app/ --include="*.php" | wc -l`
Expected: 0 (nenhum import antigo encontrado)

- [ ] **Step 2: Se encontrar imports antigos, atualizar**

Identificar arquivos com imports antigos e atualizar namespaces conforme Tasks anteriores.

- [ ] **Step 3: Remover diretórios antigos**

```bash
rm -rf app/Http/Controllers/Auth
rm -rf app/Actions/Auth
rm -rf app/Dtos/Auth
rm -rf app/Exceptions/Auth
rm app/Http/Requests/LoginRequest.php
rm app/Http/Requests/RegisterRequest.php
rm app/Http/Requests/RegisterOrganizationRequest.php
```

- [ ] **Step 4: Verificar diretórios removidos**

Run: `ls -la app/Http/Controllers/Auth 2>&1`
Expected: No such file or directory

Run: `ls -la app/Actions/Auth 2>&1`
Expected: No such file or directory

Run: `ls -la app/Dtos/Auth 2>&1`
Expected: No such file or directory

- [ ] **Step 5: Executar testes finais**

Run: `php artisan test`
Expected: todos testes passando

- [ ] **Step 6: Commit remoção de arquivos antigos**

```bash
git add -A
git commit -m "refactor(auth): remove old auth files after module migration
"
```

---

### Task 15: Atualizar Documentação

**Files:**
- Create: `app/Modules/Auth/README.md`

**Interfaces:**
- Consumes: estrutura completa do módulo
- Produces: documentação do módulo

- [ ] **Step 1: Criar README do módulo**

```markdown
# Auth Module

Módulo de autenticação isolado seguindo arquitetura modular Laravel.

## Estrutura

```
app/Modules/Auth/
├── Application/          # Camada de aplicação
│   ├── Actions/         # Actions (use cases)
│   └── DTOs/            # Data Transfer Objects
├── Domain/              # Camada de domínio
│   └── Exceptions/      # Exceções de domínio
├── Infrastructure/      # Camada de infraestrutura
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

## Testes

Execute testes do módulo:

```bash
php artisan test --filter Auth
```
```

File: `app/Modules/Auth/README.md`

- [ ] **Step 2: Commit documentação**

```bash
git add app/Modules/Auth/README.md
git commit -m "docs(auth): add auth module documentation
"
```

---

## Verification Checklist

Após completar todas tasks:

- [ ] Estrutura de diretórios criada em `app/Modules/Auth/`
- [ ] Todas exceptions movidas para `Domain/Exceptions/`
- [ ] Todos DTOs movidos para `Application/DTOs/`
- [ ] Todas Actions movidas para `Application/Actions/`
- [ ] Todos Requests movidos para `Infrastructure/Http/Requests/`
- [ ] Controller movido para `Infrastructure/Http/Controllers/`
- [ ] Arquivo de rotas criado em `Infrastructure/Routes/auth.php`
- [ ] Service Provider criado e registrado
- [ ] Rotas antigas removidas de `routes/api.php`
- [ ] Todos testes passando
- [ ] Arquivos antigos removidos
- [ ] Documentação criada
- [ ] Cache limpo e rotas verificadas

## Rollback Plan

Se algo quebrar:

1. Reverter commits usando `git revert` na ordem inversa
2. Executar `php artisan optimize:clear`
3. Executar testes: `php artisan test`

## Next Steps (Fora do Escopo)

- Adicionar testes específicos do módulo em `app/Modules/Auth/Tests/`
- Implementar Value Objects se necessário
- Adicionar Services layer se lógica de negócio crescer
- Implementar eventos de domínio (UserRegistered, UserLoggedIn, etc)
