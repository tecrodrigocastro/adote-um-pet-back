<?php

namespace App\Enums;

enum UserType: string
{
    case INDIVIDUAL = 'individual';
    case ORGANIZATION = 'organization';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Pessoa Física',
            self::ORGANIZATION => 'Organização',
        };
    }
}
