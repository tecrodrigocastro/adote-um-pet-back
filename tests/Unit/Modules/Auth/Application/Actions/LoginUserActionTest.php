<?php

use App\Models\User;
use App\Modules\Auth\Application\Actions\LoginUserAction;
use App\Modules\Auth\Application\Actions\LoginUserResult;
use App\Modules\Auth\Application\DTOs\LoginUserDto;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('authenticates user with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    $action = new LoginUserAction();
    $result = $action->handle($dto);

    expect($result)->toBeInstanceOf(LoginUserResult::class)
        ->and($result->user->id)->toBe($user->id)
        ->and($result->user->email->value())->toBe('john@example.com')
        ->and($result->token)->toBeString()
        ->and(Auth::check())->toBeTrue();
});

it('throws InvalidCredentialsException with invalid password', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('correct-password'),
    ]);

    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);

    $action = new LoginUserAction();

    expect(fn() => $action->handle($dto))
        ->toThrow(InvalidCredentialsException::class);
});

it('throws InvalidCredentialsException with non-existent email', function () {
    $dto = LoginUserDto::from([
        'email' => 'nonexistent@example.com',
        'password' => 'some-password',
    ]);

    $action = new LoginUserAction();

    expect(fn() => $action->handle($dto))
        ->toThrow(InvalidCredentialsException::class);
});

it('generates a valid Sanctum token', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    $action = new LoginUserAction();
    $result = $action->handle($dto);

    expect($result->token)->toBeString()
        ->and(strlen($result->token))->toBeGreaterThan(10);

    // Verify the token works
    Sanctum::actingAs($user);
    expect(Auth::check())->toBeTrue();
});
