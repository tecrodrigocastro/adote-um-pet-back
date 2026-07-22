<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class InvalidCredentialsException extends Exception
{
    protected $message = 'Credenciais inválidas';

    protected $code = 401;
}
