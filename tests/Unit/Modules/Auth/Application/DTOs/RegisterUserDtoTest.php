<?php

use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Domain\ValueObjects\Password;

if (! function_exists('registerPayload')) {
    function registerPayload(array $overrides = []): array
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
    $dto = RegisterUserDto::from(registerPayload());

    expect($dto->name)->toBe('John Doe')
        ->and($dto->email)->toBeInstanceOf(Email::class)
        ->and($dto->email->value())->toBe('john@example.com')
        ->and($dto->password)->toBeInstanceOf(Password::class)
        ->and($dto->password->value())->toBe('secret123')
        ->and($dto->phone)->toBeInstanceOf(\App\Modules\Auth\Domain\ValueObjects\Phone::class)
        ->and($dto->phone->value())->toBe('11999999999')
        ->and($dto->photoUrl)->toBe('https://example.com/photo.jpg')
        ->and($dto->address)->toBeInstanceOf(AddressDto::class)
        ->and($dto->address->city)->toBe('São Paulo');
});

it('defaults photo_url to null when missing', function () {
    $payload = registerPayload();
    unset($payload['photo_url']);

    expect(RegisterUserDto::from($payload)->photoUrl)->toBeNull();
});
