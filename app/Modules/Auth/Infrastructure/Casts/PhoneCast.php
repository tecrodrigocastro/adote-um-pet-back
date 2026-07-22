<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\Phone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PhoneCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Phone
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new Phone((string) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Phone) {
            return $value->value();
        }

        return (new Phone((string) $value))->value();
    }
}
