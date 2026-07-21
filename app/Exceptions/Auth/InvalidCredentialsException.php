<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

final class InvalidCredentialsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Credenciais inválidas.');
    }

    public function statusCode(): int
    {
        return 401;
    }
}
