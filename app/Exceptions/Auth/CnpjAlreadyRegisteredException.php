<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

final class CnpjAlreadyRegisteredException extends DomainException
{
    public function __construct(string $cnpj)
    {
        parent::__construct("O CNPJ {$cnpj} já está em uso.");
    }

    public function statusCode(): int
    {
        return 409;
    }
}
