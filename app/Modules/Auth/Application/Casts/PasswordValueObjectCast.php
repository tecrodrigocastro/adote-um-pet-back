<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Casts;

use App\Modules\Auth\Domain\ValueObjects\Password;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class PasswordValueObjectCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Password) {
            return $value;
        }

        return new Password((string) $value);
    }
}
