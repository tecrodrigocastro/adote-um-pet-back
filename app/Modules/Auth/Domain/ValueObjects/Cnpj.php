<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Cnpj implements Stringable
{
    private readonly string $value;

    public function __construct(string $cnpj)
    {
        $this->value = $this->validate($cnpj);
    }

    private function validate(string $cnpj): string
    {
        $normalized = $this->normalize($cnpj);

        if (!$this->isValidFormat($normalized)) {
            throw new InvalidArgumentException("Invalid CNPJ format: {$cnpj}");
        }

        if (!$this->isValidChecksum($normalized)) {
            throw new InvalidArgumentException("Invalid CNPJ: {$cnpj}");
        }

        return $normalized;
    }

    private function normalize(string $cnpj): string
    {
        return preg_replace('/\D/', '', $cnpj) ?? '';
    }

    private function isValidFormat(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Reject if all digits are the same
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        return true;
    }

    private function isValidChecksum(string $cnpj): bool
    {
        $cnpjArray = str_split($cnpj);

        // Validate first check digit
        $sum = 0;
        $multiplier = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpjArray[$i] * $multiplier;
            $multiplier--;
            if ($multiplier === 1) {
                $multiplier = 9;
            }
        }

        $firstDigit = 11 - ($sum % 11);
        $firstDigit = $firstDigit >= 10 ? 0 : $firstDigit;

        if ((int) $cnpjArray[12] !== $firstDigit) {
            return false;
        }

        // Validate second check digit
        $sum = 0;
        $multiplier = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpjArray[$i] * $multiplier;
            $multiplier--;
            if ($multiplier === 1) {
                $multiplier = 9;
            }
        }

        $secondDigit = 11 - ($sum % 11);
        $secondDigit = $secondDigit >= 10 ? 0 : $secondDigit;

        return (int) $cnpjArray[13] === $secondDigit;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $this->value
        ) ?? $this->value;
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
