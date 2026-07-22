<?php

use App\Models\User;

function validRegisterOrganizationPayload(array $overrides = []): array
{
    return array_replace([
        'email' => 'org@example.com',
        'password' => 'secret123',
        'organization_name' => 'Abrigo Patinhas Felizes',
        'cnpj' => '00000000000191',
        'responsible_name' => 'Maria Souza',
        'phone' => '1234567890',
        'mission_statement' => 'Resgatar e encontrar lares para animais abandonados.',
        'website' => 'https://patinhasfelizes.org',
        'social_media' => [
            'facebook' => 'https://facebook.com/patinhasfelizes',
        ],
        'address' => [
            'street' => 'Rua das Flores',
            'neighborhood' => 'Centro',
            'number_house' => 123,
            'complement' => 'Sala 2',
            'zip_code' => '12345-678',
            'city' => 'São Paulo',
            'state' => 'SP',
        ],
    ], $overrides);
}

it('registers a new organization and returns it through the organization resource', function () {
    $response = $this->postJson('/api/auth/register/organization', validRegisterOrganizationPayload());

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Organização cadastrada com sucesso! Aguardando verificação.',
        ])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id', 'email', 'organization_name', 'cnpj', 'responsible_name',
                'phone', 'mission_statement', 'website', 'verified',
                'addresses' => [['id', 'street', 'neighborhood', 'number_house', 'complement', 'zip_code', 'city', 'state']],
                'created_at', 'updated_at',
            ],
        ])
        ->assertJson(['data' => ['verified' => false]]);

    expect($response->json('data'))->not->toHaveKey('password');

    $this->assertDatabaseHas('users', ['email' => 'org@example.com', 'cnpj' => '00000000000191']);
    $this->assertDatabaseHas('addresses', ['city' => 'São Paulo']);
});

it('fails validation when required fields are missing', function () {
    $response = $this->postJson('/api/auth/register/organization', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password', 'organization_name', 'cnpj', 'responsible_name', 'phone', 'mission_statement', 'address']);

    $this->assertDatabaseCount('users', 0);
});

it('fails validation when the email is already registered', function () {
    User::factory()->create(['email' => 'org@example.com']);

    $response = $this->postJson('/api/auth/register/organization', validRegisterOrganizationPayload());

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    $this->assertDatabaseCount('users', 1);
});

it('fails validation when the cnpj is already registered', function () {
    User::factory()->create(['email' => 'someone-else@example.com', 'cnpj' => '00000000000191']);

    $response = $this->postJson('/api/auth/register/organization', validRegisterOrganizationPayload());

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['cnpj']);

    $this->assertDatabaseCount('users', 1);
});
