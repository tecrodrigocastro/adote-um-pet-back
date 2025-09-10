<?php

namespace App\Enums;

enum VolunteerRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case VOLUNTEER = 'volunteer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::VOLUNTEER => 'Voluntário',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                'manage_volunteers',
                'approve_adoptions',
                'create_pets',
                'edit_pets',
                'delete_pets',
                'view_reports',
                'configure_organization',
            ],
            self::MANAGER => [
                'approve_adoptions',
                'create_pets',
                'edit_pets',
                'view_reports',
            ],
            self::VOLUNTEER => [
                'create_pets',
                'edit_pets',
            ],
        };
    }
}
