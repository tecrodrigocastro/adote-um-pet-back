<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;

final class ZipCode implements ValueObject
{
    private const ZIP_CODE_LENGTH = 8;

    private readonly string $value;

    public function __construct(string $zipCode)
    {
        $this->value = $this->validate($zipCode);
    }

    private function validate(string $zipCode): string
    {
        $normalized = preg_replace('/\D/', '', $zipCode) ?? '';

        if (strlen($normalized) !== self::ZIP_CODE_LENGTH) {
            throw new InvalidArgumentException("Invalid zip code format: {$zipCode}");
        }

        return $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return preg_replace(
            '/(\d{5})(\d{3})/',
            '$1-$2',
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
