<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use App\Modules\Auth\Application\Casts\ZipCodeValueObjectCast;
use App\Modules\Auth\Domain\ValueObjects\ZipCode;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class AddressDto extends Data
{
    public function __construct(
        public readonly string $street,
        public readonly string $neighborhood,
        public readonly int $numberHouse,
        public readonly ?string $complement,
        #[WithCast(ZipCodeValueObjectCast::class)]
        public readonly ZipCode $zipCode,
        public readonly string $city,
        public readonly string $state,
    ) {}

    public static function from(mixed ...$payloads): static
    {
        // Handle snake_case to camelCase conversion for input
        $arrayable = $payloads[0] ?? [];
        if (\is_array($arrayable)) {
            $converted = [];
            foreach ($arrayable as $key => $value) {
                // Convert snake_case to camelCase
                $parts = explode('_', \is_string($key) ? $key : (string) $key);
                $first = array_shift($parts);
                $rest = array_map('ucfirst', $parts);
                $camelKey = $first . implode('', $rest);
                $converted[$camelKey] = $value;
            }
            $arrayable = $converted;
        }

        return parent::from($arrayable, ...\array_slice($payloads, 1));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'number_house' => $this->numberHouse,
            'complement' => $this->complement,
            'zip_code' => $this->zipCode->value(),
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
