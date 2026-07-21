<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

function validRegisterPayload(array $overrides = []): array
{
    return array_replace([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'phone' => '11999999999',
        'photo_url' => 'https://example.com/photo.jpg',
        'address' => [
            'street' => 'Rua das Flores',
            'neighborhood' => 'Centro',
            'number_house' => 123,
            'complement' => 'Apto 4B',
            'zip_code' => '12345-678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ],
    ], $overrides);
}

it('registers a new user and returns it through the user resource', function () {
    $response = $this->postJson('/api/auth/register', validRegisterPayload());

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Usuário cadastrado com sucesso!',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'photo_url',
                'addresses' => [
                    ['id', 'street', 'neighborhood', 'number_house', 'complement', 'zip_code', 'city', 'state'],
                ],
                'created_at',
                'updated_at',
            ],
        ]);

    $user = User::where('email', 'john@example.com')->firstOrFail();

    expect(Hash::check('secret123', $user->password))->toBeTrue();

    $this->assertDatabaseHas('addresses', [
        'user_id' => $user->id,
        'city' => 'São Paulo',
    ]);

    $response->assertJsonMissingPath('data.password');
});

it('does not leak the password hash in the response', function () {
    $response = $this->postJson('/api/auth/register', validRegisterPayload());

    expect($response->json('data'))->not->toHaveKey('password');
});

it('fails validation when required fields are missing', function () {
    $response = $this->postJson('/api/auth/register', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password', 'phone', 'address']);

    $this->assertDatabaseCount('users', 0);
});

it('fails validation when the email is already registered', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $response = $this->postJson('/api/auth/register', validRegisterPayload());

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    $this->assertDatabaseCount('users', 1);
});

it('does not persist the address when the address payload is invalid', function () {
    $response = $this->postJson('/api/auth/register', validRegisterPayload([
        'address' => [
            'street' => 'Rua das Flores',
            // missing required neighborhood, number_house, zip_code, city, state
        ],
    ]));

    $response->assertStatus(422);

    $this->assertDatabaseCount('users', 0);
    $this->assertDatabaseCount('addresses', 0);
});
