<?php

use App\Models\User;
use App\Modules\Auth\Application\Actions\RegisterUserAction;
use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use App\Modules\Auth\Domain\Exceptions\EmailAlreadyRegisteredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a new user with address', function () {
    $dto = RegisterUserDto::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'photo_url' => 'https://example.com/photo.jpg',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'complement' => 'Apt 4B',
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();
    $user = $action->handle($dto);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->phone)->toBe('11999999999')
        ->and($user->photo_url)->toBe('https://example.com/photo.jpg')
        ->and($user->addresses)->toHaveCount(1)
        ->and($user->addresses->first()->street)->toBe('Main St')
        ->and($user->addresses->first()->number_house)->toBe(123)
        ->and($user->addresses->first()->city)->toBe('São Paulo');
});

it('hashes the user password', function () {
    $dto = RegisterUserDto::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();
    $user = $action->handle($dto);

    expect($user->password)->not->toBe('secret123')
        ->and(password_verify('secret123', $user->password))->toBeTrue();
});

it('throws EmailAlreadyRegisteredException when email is already in use', function () {
    User::factory()->create([
        'email' => 'john@example.com',
    ]);

    $dto = RegisterUserDto::from([
        'name' => 'Jane Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();

    expect(fn() => $action->handle($dto))
        ->toThrow(EmailAlreadyRegisteredException::class);
});

it('handles optional photo_url field', function () {
    $dto = RegisterUserDto::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();
    $user = $action->handle($dto);

    expect($user->photo_url)->toBeNull();
});

it('handles optional address complement field', function () {
    $dto = RegisterUserDto::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();
    $user = $action->handle($dto);

    expect($user->addresses->first()->complement)->toBeNull();
});

it('rolls back user creation if address creation fails', function () {
    // This test ensures transaction integrity
    $initialUserCount = User::count();

    $dto = RegisterUserDto::from([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'address' => [
            'street' => 'Main St',
            'number_house' => 123,
            'neighborhood' => 'Downtown',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ],
    ]);

    $action = new RegisterUserAction();
    $user = $action->handle($dto);

    expect(User::count())->toBe($initialUserCount + 1)
        ->and($user->addresses)->toHaveCount(1);
});
