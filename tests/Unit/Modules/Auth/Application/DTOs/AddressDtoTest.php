<?php

use App\Modules\Auth\Application\DTOs\AddressDto;

it('builds itself from an array', function () {
    $dto = AddressDto::from([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => '123',
        'complement' => 'Apto 4B',
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);

    expect($dto->street)->toBe('Rua das Flores')
        ->and($dto->neighborhood)->toBe('Centro')
        ->and($dto->numberHouse)->toBe(123)
        ->and($dto->complement)->toBe('Apto 4B')
        ->and($dto->zipCode)->toBe('12345-678')
        ->and($dto->city)->toBe('São Paulo')
        ->and($dto->state)->toBe('SP');
});

it('casts number_house to int even when given as a numeric string', function () {
    $dto = AddressDto::from([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => '123',
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);

    expect($dto->numberHouse)->toBeInt()->toBe(123);
});

it('defaults complement to null when missing', function () {
    $dto = AddressDto::from([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => 123,
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);

    expect($dto->complement)->toBeNull();
});

it('converts back to an array using snake_case keys', function () {
    $data = [
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => 123,
        'complement' => null,
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ];

    expect(AddressDto::from($data)->toArray())->toBe($data);
});
