<?php

use App\Modules\Auth\Application\DTOs\LoginUserDto;

it('builds itself from an array', function () {
    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    expect($dto->email)->toBe('john@example.com')
        ->and($dto->password)->toBe('secret123');
});

it('exposes the credentials in the shape expected by Auth::attempt', function () {
    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    expect($dto->credentials())->toBe([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);
});
