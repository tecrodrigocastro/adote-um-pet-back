<?php

use App\Actions\Auth\RegisterUserAction;
use App\Dtos\Auth\AddressDto;
use App\Dtos\Auth\RegisterUserDto;
use App\Exceptions\Auth\EmailAlreadyRegisteredException;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function registerUserDto(): RegisterUserDto
{
    return new RegisterUserDto(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'secret123',
        phone: '11999999999',
        photoUrl: null,
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

it('creates the user and its address inside a single call', function () {
    $user = app(RegisterUserAction::class)->handle(registerUserDto());

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->exists)->toBeTrue()
        ->and($user->name)->toBe('John Doe')
        ->and($user->email->value())->toBe('john@example.com')
        ->and(Hash::check('secret123', $user->password))->toBeTrue()
        ->and($user->relationLoaded('addresses'))->toBeTrue()
        ->and($user->addresses)->toHaveCount(1)
        ->and($user->addresses->first()->city)->toBe('São Paulo');

    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    $this->assertDatabaseHas('addresses', ['user_id' => $user->id, 'city' => 'São Paulo']);
});

it('rolls back the whole transaction when address creation fails', function () {
    Address::creating(function () {
        throw new RuntimeException('boom');
    });

    expect(fn () => app(RegisterUserAction::class)->handle(registerUserDto()))
        ->toThrow(RuntimeException::class, 'boom');

    $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    $this->assertDatabaseCount('addresses', 0);
});

it('throws a domain exception when the email is already taken, without creating an address', function () {
    User::factory()->create(['email' => 'john@example.com']);

    expect(fn () => app(RegisterUserAction::class)->handle(registerUserDto()))
        ->toThrow(EmailAlreadyRegisteredException::class, 'O email john@example.com já está em uso.');

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseCount('addresses', 0);
});
