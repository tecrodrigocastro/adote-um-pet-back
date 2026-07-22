<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Password implements Stringable
{
    private readonly string $value;

    public function __construct(string $password)
    {
        $this->value = $this->validate($password);
    }

    private function validate(string $password): string
    {
        if (strlen($password) < 6) {
            throw new InvalidArgumentException('Password must contain at least 6 characters');
        }

        return $password;
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
