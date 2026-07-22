<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Domain\ValueObjects;

use App\Modules\Auth\Domain\ValueObjects\Phone;
use InvalidArgumentException;

it('creates phone with 11 digits mobile number', function () {
    $phone = new Phone('(11) 99999-8888');

    expect($phone->value())->toBe('11999998888')
        ->and($phone->formatted())->toBe('(11) 99999-8888')
        ->and((string) $phone)->toBe('11999998888');
});

it('creates phone with 10 digits landline number', function () {
    $phone = new Phone('(11) 3333-4444');

    expect($phone->value())->toBe('1133334444')
        ->and($phone->formatted())->toBe('(11) 3333-4444');
});

it('compares equality between phone instances', function () {
    $phone1 = new Phone('11999998888');
    $phone2 = new Phone('(11) 99999-8888');
    $phone3 = new Phone('11888887777');

    expect($phone1->equals($phone2))->toBeTrue()
        ->and($phone1->equals($phone3))->toBeFalse();
});

it('throws exception when phone length is invalid', function () {
    new Phone('123');
})->throws(InvalidArgumentException::class);
