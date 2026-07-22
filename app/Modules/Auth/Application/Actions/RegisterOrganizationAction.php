<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Enums\UserType;
use App\Models\User;
use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use App\Modules\Auth\Domain\Exceptions\CnpjAlreadyRegisteredException;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use App\Modules\Auth\Domain\ValueObjects\Cnpj;
use App\Modules\Auth\Domain\ValueObjects\Email;
use Illuminate\Support\Facades\DB;

final class RegisterOrganizationAction
{
    public function handle(RegisterOrganizationDto $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $this->ensureUniqueCredentials($dto->email, $dto->cnpj);

            $organization = $this->createOrganization($dto);
            $this->createAddress($organization, $dto->address);

            return $organization->load('addresses');
        });
    }

    private function ensureUniqueCredentials(Email $email, Cnpj $cnpj): void
    {
        if (User::where('email', $email->value())->exists()) {
            throw new EmailAlreadyRegisteredException($email->value());
        }

        if (User::where('cnpj', $cnpj->value())->exists()) {
            throw new CnpjAlreadyRegisteredException($cnpj->value());
        }
    }

    private function createOrganization(RegisterOrganizationDto $dto): User
    {
        return User::create([
            'email' => $dto->email,
            'password' => bcrypt($dto->password->value()),
            'user_type' => UserType::ORGANIZATION,
            'organization_name' => $dto->organizationName,
            'name' => $dto->organizationName,
            'cnpj' => $dto->cnpj,
            'responsible_name' => $dto->responsibleName,
            'phone' => $dto->phone,
            'mission_statement' => $dto->missionStatement,
            'website' => $dto->website,
            'social_media' => $dto->socialMedia?->toArray(),
            'verified' => false,
        ]);
    }

    private function createAddress(User $organization, AddressDto $address): void
    {
        $organization->addresses()->create($address->toArray());
    }
}
