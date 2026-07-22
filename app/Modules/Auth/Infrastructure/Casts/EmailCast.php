<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\Email;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class EmailCast implements CastsAttributes
{
    /**
     * Cast the given value from the database to an Email Value Object.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Email
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new Email((string) $value);
    }

    /**
     * Prepare the given value for storage in the database.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Email) {
            return $value->value();
        }

        return (new Email((string) $value))->value();
    }
}
