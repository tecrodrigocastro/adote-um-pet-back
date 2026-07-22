<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;
use Stringable;

final class Cnpj implements ValueObject
{
    private const CNPJ_LENGTH = 14;
    private const INITIAL_MULTIPLIER_FIRST = 5;
    private const INITIAL_MULTIPLIER_SECOND = 6;
    private const FIRST_CHECK_DIGIT_LOOP_LIMIT = 12;
    private const SECOND_CHECK_DIGIT_LOOP_LIMIT = 13;
    private const CHECK_DIGIT_INDEX_FIRST = 12;
    private const CHECK_DIGIT_INDEX_SECOND = 13;
    private const MULTIPLIER_MIN_VALUE = 1;
    private const MULTIPLIER_RESET_VALUE = 9;
    private const CHECKSUM_MODULO = 11;
    private const INVALID_CHECKSUM_OFFSET = 10;

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
        if (strlen($cnpj) !== self::CNPJ_LENGTH) {
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
        $firstDigit = $this->calculateCheckDigit(
            $cnpjArray,
            self::INITIAL_MULTIPLIER_FIRST,
            self::FIRST_CHECK_DIGIT_LOOP_LIMIT
        );

        if ((int) $cnpjArray[self::CHECK_DIGIT_INDEX_FIRST] !== $firstDigit) {
            return false;
        }

        // Validate second check digit
        $secondDigit = $this->calculateCheckDigit(
            $cnpjArray,
            self::INITIAL_MULTIPLIER_SECOND,
            self::SECOND_CHECK_DIGIT_LOOP_LIMIT
        );

        return (int) $cnpjArray[self::CHECK_DIGIT_INDEX_SECOND] === $secondDigit;
    }

    private function calculateCheckDigit(array $cnpjArray, int $startMultiplier, int $loopLimit): int
    {
        $sum = 0;
        $multiplier = $startMultiplier;

        for ($i = 0; $i < $loopLimit; $i++) {
            $sum += (int) $cnpjArray[$i] * $multiplier;
            $multiplier--;
            if ($multiplier === self::MULTIPLIER_MIN_VALUE) {
                $multiplier = self::MULTIPLIER_RESET_VALUE;
            }
        }

        $checkDigit = self::CHECKSUM_MODULO - ($sum % self::CHECKSUM_MODULO);
        return $checkDigit >= self::INVALID_CHECKSUM_OFFSET ? 0 : $checkDigit;
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

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
