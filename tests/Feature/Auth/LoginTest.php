<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('logs in and returns the user resource with an access token', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Login realizado com sucesso!',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'phone', 'photo_url', 'created_at', 'updated_at'],
                'access_token',
            ],
        ]);

    expect($response->json('data.user'))->not->toHaveKey('password');
});

it('rejects an invalid password with a 401 in the app error shape', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertExactJson([
            'success' => false,
            'message' => 'Credenciais inválidas.',
            'data' => null,
        ]);
});

it('rejects login for an email that does not exist', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'ghost@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(401);
});

it('fails validation when required fields are missing', function () {
    $response = $this->postJson('/api/auth/login', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});
