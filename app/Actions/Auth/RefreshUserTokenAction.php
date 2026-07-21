<?php

namespace App\Actions\Auth;

use App\Models\User;

final class RefreshUserTokenAction
{
    public function __construct(
        private readonly LogoutUserAction $logoutUserAction,
    ) {}

    public function handle(User $user): string
    {
        $this->logoutUserAction->handle($user);

        return $user->createToken('authToken')->plainTextToken;
    }
}
