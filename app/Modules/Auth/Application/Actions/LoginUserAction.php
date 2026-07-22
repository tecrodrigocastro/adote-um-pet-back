<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;
use App\Modules\Auth\Application\DTOs\LoginUserDto;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;

final class LoginUserAction
{
    public function handle(LoginUserDto $dto): LoginUserResult
    {
        if (! Auth::attempt($dto->credentials())) {
            throw new InvalidCredentialsException;
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('authToken')->plainTextToken;

        return new LoginUserResult($user, $token);
    }
}
