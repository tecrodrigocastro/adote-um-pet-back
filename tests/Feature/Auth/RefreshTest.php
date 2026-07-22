<?php

use App\Models\User;

it('refreshes the token, revoking the old one and returning a new one', function () {
    $user = User::factory()->create();
    $oldToken = $user->createToken('authToken');

    $response = $this->withHeader('Authorization', 'Bearer '.$oldToken->plainTextToken)
        ->postJson('/api/auth/refresh');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Token atualizado com sucesso!',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => ['access_token', 'token_type'],
        ]);

    expect($response->json('data.access_token'))->not->toBe($oldToken->plainTextToken);

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $oldToken->accessToken->id]);
    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('rejects refresh without a valid token', function () {
    $response = $this->postJson('/api/auth/refresh');

    $response->assertStatus(401);
});
