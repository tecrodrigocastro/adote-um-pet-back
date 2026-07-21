<?php

namespace App\Actions\Auth;

use App\Dtos\Auth\LoginUserDto;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
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
