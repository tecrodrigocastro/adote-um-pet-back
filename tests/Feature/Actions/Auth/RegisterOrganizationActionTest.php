<?php

use App\Actions\Auth\RegisterOrganizationAction;
use App\Dtos\Auth\AddressDto;
use App\Dtos\Auth\RegisterOrganizationDto;
use App\Dtos\Auth\SocialMediaDto;
use App\Enums\UserType;
use App\Exceptions\Auth\CnpjAlreadyRegisteredException;
use App\Exceptions\Auth\EmailAlreadyRegisteredException;
use App\Models\Address;
use App\Models\User;

function registerOrganizationDto(): RegisterOrganizationDto
{
    return new RegisterOrganizationDto(
        email: 'org@example.com',
        password: 'secret123',
        organizationName: 'Abrigo Patinhas Felizes',
        cnpj: '00000000000191',
        responsibleName: 'Maria Souza',
        phone: '1234567890',
        missionStatement: 'Resgatar e encontrar lares para animais abandonados.',
        website: 'https://patinhasfelizes.org',
        socialMedia: new SocialMediaDto(
            facebook: 'https://facebook.com/patinhasfelizes',
            instagram: null,
            twitter: null,
            linkedin: null,
        ),
        address: new AddressDto(
            street: 'Rua das Flores',
            neighborhood: 'Centro',
            numberHouse: 123,
            complement: null,
            zipCode: '12345-678',
            city: 'São Paulo',
            state: 'SP',
        ),
    );
}

it('creates the organization and its address inside a single call', function () {
    $organization = app(RegisterOrganizationAction::class)->handle(registerOrganizationDto());

    expect($organization)->toBeInstanceOf(User::class)
        ->and($organization->exists)->toBeTrue()
        ->and($organization->user_type)->toBe(UserType::ORGANIZATION)
        ->and($organization->email->value())->toBe('org@example.com')
        ->and($organization->organization_name)->toBe('Abrigo Patinhas Felizes')
        ->and($organization->cnpj->value())->toBe('00000000000191')
        ->and($organization->verified)->toBeFalse()
        ->and($organization->social_media)->toBe([
            'facebook' => 'https://facebook.com/patinhasfelizes',
            'instagram' => null,
            'twitter' => null,
            'linkedin' => null,
        ])
        ->and($organization->relationLoaded('addresses'))->toBeTrue()
        ->and($organization->addresses)->toHaveCount(1)
        ->and($organization->addresses->first()->city)->toBe('São Paulo');

    $this->assertDatabaseHas('users', ['email' => 'org@example.com', 'cnpj' => '00000000000191']);
    $this->assertDatabaseHas('addresses', ['user_id' => $organization->id, 'city' => 'São Paulo']);
});

it('rolls back the whole transaction when address creation fails', function () {
    Address::creating(function () {
        throw new RuntimeException('boom');
    });

    expect(fn () => app(RegisterOrganizationAction::class)->handle(registerOrganizationDto()))
        ->toThrow(RuntimeException::class, 'boom');

    $this->assertDatabaseMissing('users', ['email' => 'org@example.com']);
    $this->assertDatabaseCount('addresses', 0);
});

it('throws a domain exception when the email is already taken', function () {
    User::factory()->create(['email' => 'org@example.com']);

    expect(fn () => app(RegisterOrganizationAction::class)->handle(registerOrganizationDto()))
        ->toThrow(EmailAlreadyRegisteredException::class, 'O email org@example.com já está em uso.');

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('addresses', 0);
});

it('throws a domain exception when the cnpj is already taken', function () {
    User::factory()->create(['email' => 'someone-else@example.com', 'cnpj' => '00000000000191']);

    expect(fn () => app(RegisterOrganizationAction::class)->handle(registerOrganizationDto()))
        ->toThrow(CnpjAlreadyRegisteredException::class, 'O CNPJ 00000000000191 já está em uso.');

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('addresses', 0);
});
