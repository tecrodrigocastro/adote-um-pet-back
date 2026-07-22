<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Domain\ValueObjects;

use App\Modules\Auth\Domain\ValueObjects\ZipCode;
use InvalidArgumentException;

it('creates zip code with formatted or unformatted string', function () {
    $zipCode = new ZipCode('12345-678');

    expect($zipCode->value())->toBe('12345678')
        ->and($zipCode->formatted())->toBe('12345-678')
        ->and((string) $zipCode)->toBe('12345678');
});

it('compares equality between zip code instances', function () {
    $zip1 = new ZipCode('12345678');
    $zip2 = new ZipCode('12345-678');
    $zip3 = new ZipCode('87654321');

    expect($zip1->equals($zip2))->toBeTrue()
        ->and($zip1->equals($zip3))->toBeFalse();
});

it('throws exception when zip code length is invalid', function () {
    new ZipCode('123');
})->throws(InvalidArgumentException::class);
