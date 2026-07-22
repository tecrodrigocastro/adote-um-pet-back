<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\Cnpj;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CnpjCast implements CastsAttributes
{
    /**
     * Cast the given value from the database to a Cnpj Value Object.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Cnpj
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new Cnpj((string) $value);
    }

    /**
     * Prepare the given value for storage in the database.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Cnpj) {
            return $value->value();
        }

        return (new Cnpj((string) $value))->value();
    }
}
