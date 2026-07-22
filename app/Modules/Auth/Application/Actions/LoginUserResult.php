<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;

final readonly class LoginUserResult
{
    public function __construct(
        public User $user,
        public string $token,
    ) {}
}
