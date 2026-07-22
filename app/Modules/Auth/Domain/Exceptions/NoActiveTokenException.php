<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class NoActiveTokenException extends Exception
{
    protected $message = 'Nenhum token ativo encontrado';

    protected $code = 401;
}
