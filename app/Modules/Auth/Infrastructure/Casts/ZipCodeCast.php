<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\ZipCode;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ZipCodeCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ZipCode
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new ZipCode((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof ZipCode) {
            return $value->value();
        }

        return (new ZipCode((string) $value))->value();
    }
}
