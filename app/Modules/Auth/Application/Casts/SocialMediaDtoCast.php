<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Casts;

use App\Modules\Auth\Application\DTOs\SocialMediaDto;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class SocialMediaDtoCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof SocialMediaDto) {
            return $value;
        }

        if (! is_array($value)) {
            return null;
        }

        return SocialMediaDto::from($value);
    }
}
