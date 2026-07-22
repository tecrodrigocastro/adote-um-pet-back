<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;

final class LogoutUserAction
{
    public function handle(User $user): void
    {
        $user->tokens()->delete();
    }
}
