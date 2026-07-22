<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Casts;

use App\Modules\Auth\Domain\ValueObjects\ZipCode;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class ZipCodeValueObjectCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ZipCode) {
            return $value;
        }

        return new ZipCode((string) $value);
    }
}
