<?php

namespace App\Enums;

enum PetSize: string
{
    case SMALL = 'small';
    case MEDIUM = 'medium';
    case LARGE = 'large';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SMALL => 'Pequeno',
            self::MEDIUM => 'Médio',
            self::LARGE => 'Grande',
        };
    }
}
