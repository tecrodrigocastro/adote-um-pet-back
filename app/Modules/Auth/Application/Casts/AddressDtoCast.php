<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Casts;

use App\Modules\Auth\Application\DTOs\AddressDto;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class AddressDtoCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof AddressDto) {
            return $value;
        }

        if (! is_array($value)) {
            return null;
        }

        // Handle snake_case to camelCase conversion
        return AddressDto::from([
            'street' => $value['street'] ?? null,
            'neighborhood' => $value['neighborhood'] ?? null,
            'numberHouse' => $value['number_house'] ?? null,
            'complement' => $value['complement'] ?? null,
            'zipCode' => $value['zip_code'] ?? null,
            'city' => $value['city'] ?? null,
            'state' => $value['state'] ?? null,
        ]);
    }
}
