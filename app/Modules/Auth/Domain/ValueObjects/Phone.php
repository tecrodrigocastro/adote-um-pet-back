<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;

final class Phone implements ValueObject
{
    private const MIN_LENGTH = 10;
    private const MAX_LENGTH = 11;

    private readonly string $value;

    public function __construct(string $phone)
    {
        $this->value = $this->validate($phone);
    }

    private function validate(string $phone): string
    {
        $normalized = preg_replace('/\D/', '', $phone) ?? '';

        $length = strlen($normalized);
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new InvalidArgumentException("Invalid phone number format: {$phone}");
        }

        return $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        if (strlen($this->value) === 11) {
            return preg_replace(
                '/(\d{2})(\d{5})(\d{4})/',
                '($1) $2-$3',
                $this->value
            ) ?? $this->value;
        }

        return preg_replace(
            '/(\d{2})(\d{4})(\d{4})/',
            '($1) $2-$3',
            $this->value
        ) ?? $this->value;
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
