<?php

namespace App\Actions\Auth;

use App\Dtos\Auth\AddressDto;
use App\Dtos\Auth\RegisterUserDto;
use App\Exceptions\Auth\EmailAlreadyRegisteredException;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

final class RegisterUserAction
{
    public function handle(RegisterUserDto $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $user = $this->createUser($dto);
            $this->createAddress($user, $dto->address);

            return $user->load('addresses');
        });
    }

    private function createUser(RegisterUserDto $dto): User
    {
        try {
            return User::create([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => bcrypt($dto->password),
                'phone' => $dto->phone,
                'photo_url' => $dto->photoUrl,
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() !== '23000') {
                throw $e;
            }

            throw new EmailAlreadyRegisteredException($dto->email);
        }
    }

    private function createAddress(User $user, AddressDto $address): void
    {
        $user->addresses()->create($address->toArray());
    }
}
