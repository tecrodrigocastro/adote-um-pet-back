<?php

use App\Models\User;

it('logs out and revokes the token used in the request', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken');

    $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
        ->postJson('/api/auth/logout');

    $response->assertOk()
        ->assertExactJson([
            'success' => true,
            'message' => 'Logout realizado com sucesso!',
            'data' => [],
        ]);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);
});

it('rejects logout without a valid token', function () {
    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(401);
});
