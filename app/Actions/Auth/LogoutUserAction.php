<?php

namespace App\Actions\Auth;

use App\Exceptions\Auth\NoActiveTokenException;
use App\Models\User;

final class LogoutUserAction
{
    public function handle(User $user): void
    {
        $token = $user->currentAccessToken();

        if (!$token) {
            throw new NoActiveTokenException;
        }

        $token->delete();
    }
}
