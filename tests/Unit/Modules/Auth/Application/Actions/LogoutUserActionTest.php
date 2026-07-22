<?php

use App\Models\User;
use App\Modules\Auth\Application\Actions\LogoutUserAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('revokes all user tokens', function () {
    $user = User::factory()->create();

    // Create multiple tokens
    $token1 = $user->createToken('token1')->plainTextToken;
    $token2 = $user->createToken('token2')->plainTextToken;
    $token3 = $user->createToken('token3')->plainTextToken;

    expect($user->tokens()->count())->toBe(3);

    $action = new LogoutUserAction();
    $action->handle($user);

    expect($user->tokens()->count())->toBe(0);
});

it('handles user with no tokens gracefully', function () {
    $user = User::factory()->create();

    expect($user->tokens()->count())->toBe(0);

    $action = new LogoutUserAction();
    $action->handle($user);

    expect($user->tokens()->count())->toBe(0);
});

it('only revokes tokens for the specified user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1->createToken('user1-token');
    $user2->createToken('user2-token');

    expect($user1->tokens()->count())->toBe(1)
        ->and($user2->tokens()->count())->toBe(1);

    $action = new LogoutUserAction();
    $action->handle($user1);

    expect($user1->tokens()->count())->toBe(0)
        ->and($user2->tokens()->count())->toBe(1);
});

it('revokes active session token', function () {
    $user = User::factory()->create();

    // Create an actual token (actingAs doesn't create database token)
    $user->createToken('session-token');

    expect($user->tokens()->count())->toBe(1);

    $action = new LogoutUserAction();
    $action->handle($user);

    expect($user->tokens()->count())->toBe(0);
});
