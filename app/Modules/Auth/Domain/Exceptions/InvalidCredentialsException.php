<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidCredentialsException extends HttpException
{
    public function __construct()
    {
        parent::__construct(401, 'Credenciais inválidas.');
    }
}
