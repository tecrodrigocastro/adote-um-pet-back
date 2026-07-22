<?php

use App\Modules\Auth\Application\DTOs\LoginUserDto;
use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Domain\ValueObjects\Password;

it('builds itself from an array', function () {
    $dto = LoginUserDto::from([
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    expect($dto->email)->toBeInstanceOf(Email::class)
        ->and($dto->email->value())->toBe('john@example.com')
        ->and($dto->password)->toBeInstanceOf(Password::class)
        ->and($dto->password->value())->toBe('secret123');
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
