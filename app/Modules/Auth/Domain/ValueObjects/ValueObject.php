<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use Stringable;

interface ValueObject extends Stringable
{
    public function value(): mixed;

    public function equals(self $other): bool;
}
