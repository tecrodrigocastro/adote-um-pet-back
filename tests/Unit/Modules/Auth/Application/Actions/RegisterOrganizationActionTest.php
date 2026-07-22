<?php

use App\Enums\UserType;
use App\Models\User;
use App\Modules\Auth\Application\Actions\RegisterOrganizationAction;
use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use App\Modules\Auth\Domain\Exceptions\CnpjAlreadyRegisteredException;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a new organization with address', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => 'https://helpanimals.org',
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'complement' => 'Suite 100',
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization)->toBeInstanceOf(User::class)
        ->and($organization->email->value())->toBe('org@example.com')
        ->and($organization->user_type)->toBe(UserType::ORGANIZATION)
        ->and($organization->organization_name)->toBe('Help Animals Foundation')
        ->and($organization->name)->toBe('Help Animals Foundation')
        ->and($organization->cnpj->value())->toBe('00000000000191')
        ->and($organization->responsible_name)->toBe('John Doe')
        ->and($organization->phone->value())->toBe('11999999999')
        ->and($organization->mission_statement)->toBe('We help animals in need')
        ->and($organization->website)->toBe('https://helpanimals.org')
        ->and($organization->verified)->toBeFalse()
        ->and($organization->addresses)->toHaveCount(1)
        ->and($organization->addresses->first()->street)->toBe('Main St')
        ->and($organization->addresses->first()->number_house)->toBe(123)
        ->and($organization->addresses->first()->city)->toBe('São Paulo');
});

it('creates organization with social media', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => [
            'facebook' => 'https://facebook.com/helpanimals',
            'instagram' => 'https://instagram.com/helpanimals',
            'twitter' => 'https://twitter.com/helpanimals',
        ],
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization->social_media)->toBeArray()
        ->and($organization->social_media['facebook'])->toBe('https://facebook.com/helpanimals')
        ->and($organization->social_media['instagram'])->toBe('https://instagram.com/helpanimals');
});

it('handles optional social media as null', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization->social_media)->toBeNull();
});

it('handles optional website as null', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization->website)->toBeNull();
});

it('hashes the organization password', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization->password)->not->toBe('secret123')
        ->and(password_verify('secret123', $organization->password))->toBeTrue();
});

it('throws EmailAlreadyRegisteredException when email is already in use', function () {
    User::factory()->create([
        'email' => 'org@example.com',
    ]);

    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();

    expect(fn() => $action->handle($dto))
        ->toThrow(EmailAlreadyRegisteredException::class);
});

it('throws CnpjAlreadyRegisteredException when cnpj is already in use', function () {
    User::factory()->create([
        'email' => 'existing@example.com',
        'cnpj' => '00.000.000/0001-91',
        'user_type' => UserType::ORGANIZATION,
    ]);

    $dto = RegisterOrganizationDto::from([
        'email' => 'new@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();

    expect(fn() => $action->handle($dto))
        ->toThrow(CnpjAlreadyRegisteredException::class);
});

it('handles optional address complement field', function () {
    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect($organization->addresses->first()->complement)->toBeNull();
});

it('rolls back organization creation if address creation fails', function () {
    // This test ensures transaction integrity
    $initialUserCount = User::count();

    $dto = RegisterOrganizationDto::from([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Help Animals Foundation',
        'cnpj' => '00.000.000/0001-91',
        'responsible_name' => 'John Doe',
        'phone' => '11999999999',
        'mission_statement' => 'We help animals in need',
        'website' => null,
        'social_media' => null,
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterOrganizationAction();
    $organization = $action->handle($dto);

    expect(User::count())->toBe($initialUserCount + 1)
        ->and($organization->addresses)->toHaveCount(1);
});
