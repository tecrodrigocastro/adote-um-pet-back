<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use App\Modules\Auth\Application\Casts\AddressDtoCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class RegisterUserDto extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone,
        public readonly ?string $photoUrl,
        #[WithCast(AddressDtoCast::class)]
        public readonly AddressDto $address,
    ) {}

    public static function from(mixed $arrayable = [], ...$args): static
    {
        // Handle snake_case to camelCase conversion for input
        if (is_array($arrayable)) {
            $converted = [];
            foreach ($arrayable as $key => $value) {
                // Convert snake_case to camelCase
                $parts = explode('_', is_string($key) ? $key : (string) $key);
                $first = array_shift($parts);
                $rest = array_map('ucfirst', $parts);
                $camelKey = $first . implode('', $rest);
                $converted[$camelKey] = $value;
            }
            $arrayable = $converted;
        }

        return parent::from($arrayable, ...$args);
    }
}
