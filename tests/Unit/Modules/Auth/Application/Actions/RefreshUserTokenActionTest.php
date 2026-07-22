<?php

use App\Models\User;
use App\Modules\Auth\Application\Actions\RefreshUserTokenAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('revokes old tokens and creates new token', function () {
    $user = User::factory()->create();

    // Create an old token
    $oldToken = $user->createToken('oldToken')->plainTextToken;

    expect($user->tokens()->count())->toBe(1);

    $action = app(RefreshUserTokenAction::class);
    $newToken = $action->handle($user);

    expect($newToken)->toBeString()
        ->and($newToken)->not->toBe($oldToken)
        ->and($user->tokens()->count())->toBe(1);
});

it('revokes multiple old tokens and creates single new token', function () {
    $user = User::factory()->create();

    // Create multiple old tokens
    $user->createToken('token1');
    $user->createToken('token2');
    $user->createToken('token3');

    expect($user->tokens()->count())->toBe(3);

    $action = app(RefreshUserTokenAction::class);
    $newToken = $action->handle($user);

    expect($newToken)->toBeString()
        ->and($user->tokens()->count())->toBe(1);
});

it('returns a valid Sanctum token', function () {
    $user = User::factory()->create();

    $action = app(RefreshUserTokenAction::class);
    $token = $action->handle($user);

    expect($token)->toBeString()
        ->and(strlen($token))->toBeGreaterThan(10);

    // Verify token format (should have pipe separator)
    expect($token)->toContain('|');
});

it('creates new token when user has no previous tokens', function () {
    $user = User::factory()->create();

    expect($user->tokens()->count())->toBe(0);

    $action = app(RefreshUserTokenAction::class);
    $newToken = $action->handle($user);

    expect($newToken)->toBeString()
        ->and($user->tokens()->count())->toBe(1);
});

it('new token works for authentication', function () {
    $user = User::factory()->create();

    $action = app(RefreshUserTokenAction::class);
    $newToken = $action->handle($user);

    // Verify the new token can be used for authentication
    Sanctum::actingAs($user);

    expect(auth()->check())->toBeTrue()
        ->and(auth()->id())->toBe($user->id);
});
