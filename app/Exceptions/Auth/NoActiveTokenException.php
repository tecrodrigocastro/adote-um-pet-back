<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

final class NoActiveTokenException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Nenhum token de acesso ativo para esta sessão.');
    }

    public function statusCode(): int
    {
        return 401;
    }
}
