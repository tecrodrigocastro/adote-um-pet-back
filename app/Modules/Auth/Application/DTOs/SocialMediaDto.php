<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

use Spatie\LaravelData\Data;

final class SocialMediaDto extends Data
{
    public function __construct(
        public readonly ?string $facebook,
        public readonly ?string $instagram,
        public readonly ?string $twitter,
        public readonly ?string $linkedin,
    ) {}

    /**
     * @return array{facebook: ?string, instagram: ?string, twitter: ?string, linkedin: ?string}
     */
    public function toArray(): array
    {
        return [
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
        ];
    }
}
