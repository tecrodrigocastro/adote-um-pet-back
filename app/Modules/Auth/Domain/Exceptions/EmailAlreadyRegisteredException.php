<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Exceptions;

use Exception;

final class EmailAlreadyRegisteredException extends Exception
{
    protected $message = 'Email já cadastrado';

    protected $code = 409;
}
