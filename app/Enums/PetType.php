<?php

namespace App\Enums;

enum PetType: string
{
    case DOG = 'dog';
    case CAT = 'cat';
    case RABBIT = 'rabbit';
    case BIRD = 'bird';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::DOG => 'Cachorro',
            self::CAT => 'Gato',
            self::RABBIT => 'Coelho',
            self::BIRD => 'Pássaro',
            self::OTHER => 'Outro',
        };
    }
}
