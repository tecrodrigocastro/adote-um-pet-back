<?php

namespace App\Exceptions\Auth;

use App\Exceptions\DomainException;

final class EmailAlreadyRegisteredException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("O email {$email} já está em uso.");
    }

    public function statusCode(): int
    {
        return 409;
    }
}
