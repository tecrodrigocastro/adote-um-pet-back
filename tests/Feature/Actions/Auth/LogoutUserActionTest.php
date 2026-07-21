<?php

use App\Actions\Auth\LogoutUserAction;
use App\Exceptions\Auth\NoActiveTokenException;
use App\Models\User;

it('deletes the current access token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken');
    $user->withAccessToken($token->accessToken);

    app(LogoutUserAction::class)->handle($user);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
});

it('does not delete other tokens belonging to the same user', function () {
    $user = User::factory()->create();
    $currentToken = $user->createToken('authToken');
    $otherToken = $user->createToken('otherDevice');
    $user->withAccessToken($currentToken->accessToken);

    app(LogoutUserAction::class)->handle($user);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
    $this->assertDatabaseHas('personal_access_tokens', ['id' => $otherToken->accessToken->id]);
});

it('throws a domain exception when there is no active token', function () {
    $user = User::factory()->create();

    expect(fn () => app(LogoutUserAction::class)->handle($user))
        ->toThrow(NoActiveTokenException::class, 'Nenhum token de acesso ativo para esta sessão.');
});
