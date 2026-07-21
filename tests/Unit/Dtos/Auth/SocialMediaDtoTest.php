<?php

use App\Dtos\Auth\SocialMediaDto;

it('builds itself from an array', function () {
    $dto = SocialMediaDto::fromArray([
        'facebook' => 'https://facebook.com/patinhasfelizes',
        'instagram' => 'https://instagram.com/patinhasfelizes',
        'twitter' => null,
        'linkedin' => null,
    ]);

    expect($dto->facebook)->toBe('https://facebook.com/patinhasfelizes')
        ->and($dto->instagram)->toBe('https://instagram.com/patinhasfelizes')
        ->and($dto->twitter)->toBeNull()
        ->and($dto->linkedin)->toBeNull();
});

it('defaults missing keys to null', function () {
    $dto = SocialMediaDto::fromArray(['facebook' => 'https://facebook.com/patinhasfelizes']);

    expect($dto->facebook)->toBe('https://facebook.com/patinhasfelizes')
        ->and($dto->instagram)->toBeNull()
        ->and($dto->twitter)->toBeNull()
        ->and($dto->linkedin)->toBeNull();
});

it('returns null when given null', function () {
    expect(SocialMediaDto::fromArray(null))->toBeNull();
});

it('converts back to an array', function () {
    $data = [
        'facebook' => 'https://facebook.com/patinhasfelizes',
        'instagram' => null,
        'twitter' => null,
        'linkedin' => null,
    ];

    expect(SocialMediaDto::fromArray($data)->toArray())->toBe($data);
});
