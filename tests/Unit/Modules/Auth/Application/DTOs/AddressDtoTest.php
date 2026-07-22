<?php

use App\Modules\Auth\Application\DTOs\AddressDto;
use App\Modules\Auth\Domain\ValueObjects\ZipCode;

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
        ->and($dto->zipCode)->toBeInstanceOf(ZipCode::class)
        ->and($dto->zipCode->value())->toBe('12345678')
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
    $dto = AddressDto::from([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => 123,
        'complement' => 'Apto 4B',
        'zip_code' => '12345-678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);

    expect($dto->toArray())->toBe([
        'street' => 'Rua das Flores',
        'neighborhood' => 'Centro',
        'number_house' => 123,
        'complement' => 'Apto 4B',
        'zip_code' => '12345678',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);
});
