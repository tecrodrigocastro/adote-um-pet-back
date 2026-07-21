<?php

use App\Actions\Auth\RefreshUserTokenAction;
use App\Exceptions\Auth\NoActiveTokenException;
use App\Models\User;

it('revokes the current token and issues a new one', function () {
    $user = User::factory()->create();
    $oldToken = $user->createToken('authToken');
    $user->withAccessToken($oldToken->accessToken);

    $newPlainTextToken = app(RefreshUserTokenAction::class)->handle($user);

    expect($newPlainTextToken)->toBeString()->not->toBe($oldToken->plainTextToken);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $oldToken->accessToken->id]);
    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('throws a domain exception when there is no active token', function () {
    $user = User::factory()->create();

    expect(fn () => app(RefreshUserTokenAction::class)->handle($user))
        ->toThrow(NoActiveTokenException::class);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
