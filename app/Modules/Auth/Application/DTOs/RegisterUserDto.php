<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use App\Modules\Auth\Application\Casts\AddressDtoCast;
use App\Modules\Auth\Application\Casts\EmailValueObjectCast;
use App\Modules\Auth\Application\Casts\PasswordValueObjectCast;
use App\Modules\Auth\Application\Casts\PhoneValueObjectCast;
use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Domain\ValueObjects\Password;
use App\Modules\Auth\Domain\ValueObjects\Phone;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class RegisterUserDto extends Data
{
    public function __construct(
        public readonly string $name,
        #[WithCast(EmailValueObjectCast::class)]
        public readonly Email $email,
        #[WithCast(PasswordValueObjectCast::class)]
        public readonly Password $password,
        #[WithCast(PhoneValueObjectCast::class)]
        public readonly Phone $phone,
        public readonly ?string $photoUrl,
        #[WithCast(AddressDtoCast::class)]
        public readonly AddressDto $address,
    ) {}

    public static function from(mixed ...$payloads): static
    {
        // Extract first argument to match parent signature
        $arrayable = $payloads[0] ?? [];

        // Handle snake_case to camelCase conversion for input
        if (\is_array($arrayable)) {
            $converted = [];
            foreach ($arrayable as $key => $value) {
                // Convert snake_case to camelCase
                $parts = \explode('_', \is_string($key) ? $key : (string) $key);
                $first = \array_shift($parts);
                $rest = \array_map('ucfirst', $parts);
                $camelKey = $first . \implode('', $rest);
                $converted[$camelKey] = $value;
            }
            $arrayable = $converted;
        }

        return parent::from($arrayable, ...\array_slice($payloads, 1));
    }
}
