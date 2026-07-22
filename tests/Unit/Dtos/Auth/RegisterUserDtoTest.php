<?php

use App\Dtos\Auth\AddressDto;
use App\Dtos\Auth\RegisterUserDto;

if (! function_exists('legacyRegisterPayload')) {
    function legacyRegisterPayload(array $overrides = []): array
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
                'complement' => null,
                'zip_code' => '12345-678',
                'city' => 'São Paulo',
                'state' => 'SP',
            ],
        ], $overrides);
    }
}

it('builds itself from an array, including the nested address dto', function () {
    $dto = RegisterUserDto::fromArray(legacyRegisterPayload());

    expect($dto->name)->toBe('John Doe')
        ->and($dto->email)->toBe('john@example.com')
        ->and($dto->password)->toBe('secret123')
        ->and($dto->phone)->toBe('11999999999')
        ->and($dto->photoUrl)->toBe('https://example.com/photo.jpg')
        ->and($dto->address)->toBeInstanceOf(AddressDto::class)
        ->and($dto->address->city)->toBe('São Paulo');
});

it('defaults photo_url to null when missing', function () {
    $payload = legacyRegisterPayload();
    unset($payload['photo_url']);

    expect(RegisterUserDto::fromArray($payload)->photoUrl)->toBeNull();
});
