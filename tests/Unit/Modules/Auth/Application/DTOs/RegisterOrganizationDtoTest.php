<?php

use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use App\Modules\Auth\Application\DTOs\SocialMediaDto;

function registerOrganizationPayload(array $overrides = []): array
{
    return array_replace([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Abrigo Patinhas Felizes',
        'cnpj' => '12345678000199',
        'responsible_name' => 'Maria Souza',
        'phone' => '1234567890',
        'mission_statement' => 'Resgatar e encontrar lares para animais abandonados.',
        'website' => 'https://patinhasfelizes.org',
        'social_media' => ['facebook' => 'https://facebook.com/patinhasfelizes'],
        'address' => [
            'street' => 'Rua das Flores',
            'neighborhood' => 'Centro',
            'number_house' => 123,
            'complement' => null,
            'zip_code' => '12345-678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ],
    ], $overrides);
}

it('builds itself from an array, including the nested address and social media dtos', function () {
    $dto = RegisterOrganizationDto::from(registerOrganizationPayload());

    expect($dto->email)->toBe('org@example.com')
        ->and($dto->password)->toBe('secret123')
        ->and($dto->organizationName)->toBe('Abrigo Patinhas Felizes')
        ->and($dto->cnpj)->toBe('12345678000199')
        ->and($dto->responsibleName)->toBe('Maria Souza')
        ->and($dto->phone)->toBe('1234567890')
        ->and($dto->missionStatement)->toBe('Resgatar e encontrar lares para animais abandonados.')
        ->and($dto->website)->toBe('https://patinhasfelizes.org')
        ->and($dto->socialMedia)->toBeInstanceOf(SocialMediaDto::class)
        ->and($dto->socialMedia->facebook)->toBe('https://facebook.com/patinhasfelizes')
        ->and($dto->address)->toBeInstanceOf(AddressDto::class)
        ->and($dto->address->city)->toBe('São Paulo');
});

it('defaults website and social_media to null when missing', function () {
    $payload = registerOrganizationPayload();
    unset($payload['website'], $payload['social_media']);

    $dto = RegisterOrganizationDto::from($payload);

    expect($dto->website)->toBeNull()
        ->and($dto->socialMedia)->toBeNull();
});
