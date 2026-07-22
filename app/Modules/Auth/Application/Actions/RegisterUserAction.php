<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;
use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use App\Modules\Auth\Domain\ValueObjects\Email;
use Illuminate\Support\Facades\DB;

final class RegisterUserAction
{
    public function handle(RegisterUserDto $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $this->ensureEmailIsUnique($dto->email);

            $user = $this->createUser($dto);
            $this->createAddress($user, $dto->address);

            return $user->load('addresses');
        });
    }

    private function ensureEmailIsUnique(Email $email): void
    {
        if (User::where('email', $email->value())->exists()) {
            throw new EmailAlreadyRegisteredException($email->value());
        }
    }

    private function createUser(RegisterUserDto $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password->value()),
            'phone' => $dto->phone,
            'photo_url' => $dto->photoUrl,
        ]);
    }

    private function createAddress(User $user, AddressDto $address): void
    {
        $user->addresses()->create($address->toArray());
    }
}
