<?php

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LoginUserResult;
use App\Dtos\Auth\LoginUserDto;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('authenticates the user and issues an access token', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $result = app(LoginUserAction::class)->handle(new LoginUserDto(
        email: 'john@example.com',
        password: 'secret123',
    ));

    expect($result)->toBeInstanceOf(LoginUserResult::class)
        ->and($result->user->is($user))->toBeTrue()
        ->and($result->token)->toBeString()->not->toBeEmpty();

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => User::class,
    ]);
});

it('throws a domain exception and issues no token when the password is wrong', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    expect(fn () => app(LoginUserAction::class)->handle(new LoginUserDto(
        email: 'john@example.com',
        password: 'wrong-password',
    )))->toThrow(InvalidCredentialsException::class, 'Credenciais inválidas.');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

it('throws a domain exception when the email does not exist', function () {
    expect(fn () => app(LoginUserAction::class)->handle(new LoginUserDto(
        email: 'ghost@example.com',
        password: 'secret123',
    )))->toThrow(InvalidCredentialsException::class);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
