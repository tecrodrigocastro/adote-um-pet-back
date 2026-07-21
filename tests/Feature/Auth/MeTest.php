<?php

use App\Models\User;

it('returns the authenticated user through the user resource', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;
    $user->addresses()->create([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => 123,
        'complement' => null,
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/auth/me');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Usuário autenticado',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id', 'name', 'email', 'phone', 'photo_url',
                'addresses' => [['id', 'street', 'neighborhood', 'number_house', 'complement', 'zip_code', 'city', 'state']],
                'created_at', 'updated_at',
            ],
        ]);

    expect($response->json('data'))->not->toHaveKey('password');
});

it('rejects the request without a valid token', function () {
    $response = $this->getJson('/api/auth/me');

    $response->assertStatus(401);
});
