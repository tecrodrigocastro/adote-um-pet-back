<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Actions;

use App\Enums\UserType;
use App\Models\User;
use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use App\Modules\Auth\Domain\Exceptions\CnpjAlreadyRegisteredException;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

final class RegisterOrganizationAction
{
    public function handle(RegisterOrganizationDto $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $organization = $this->createOrganization($dto);
            $this->createAddress($organization, $dto->address);

            return $organization->load('addresses');
        });
    }

    private function createOrganization(RegisterOrganizationDto $dto): User
    {
        try {
            return User::create([
                'email' => $dto->email,
                'password' => bcrypt($dto->password),
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
        } catch (QueryException $e) {
            if ($e->getCode() !== '23000') {
                throw $e;
            }

            if (User::where('email', $dto->email)->exists()) {
                throw new EmailAlreadyRegisteredException($dto->email);
            }

            throw new CnpjAlreadyRegisteredException($dto->cnpj);
        }
    }

    private function createAddress(User $organization, AddressDto $address): void
    {
        $organization->addresses()->create($address->toArray());
    }
}
