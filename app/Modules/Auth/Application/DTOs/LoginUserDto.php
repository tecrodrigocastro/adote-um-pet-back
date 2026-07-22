<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use App\Modules\Auth\Application\Casts\EmailValueObjectCast;
use App\Modules\Auth\Application\Casts\PasswordValueObjectCast;
use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Domain\ValueObjects\Password;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class LoginUserDto extends Data
{
    public function __construct(
        #[WithCast(EmailValueObjectCast::class)]
        public readonly Email $email,
        #[WithCast(PasswordValueObjectCast::class)]
        public readonly Password $password,
    ) {}

    /**
     * @return array{email: string, password: string}
     */
    public function credentials(): array
    {
        return [
            'email' => $this->email->value(),
            'password' => $this->password->value(),
        ];
    }
}
