<?php

namespace App\Actions\Auth;

use App\Models\User;

final readonly class LoginUserResult
{
    public function __construct(
        public User $user,
        public string $token,
    ) {}
}
