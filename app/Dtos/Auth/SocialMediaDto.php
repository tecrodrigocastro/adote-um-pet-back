<?php

namespace App\Dtos\Auth;

final readonly class SocialMediaDto
{
    public function __construct(
        public ?string $facebook,
        public ?string $instagram,
        public ?string $twitter,
        public ?string $linkedin,
    ) {}

    /**
     * @param  array<string, mixed>|null  $data
     */
    public static function fromArray(?array $data): ?self
    {
        if ($data === null) {
            return null;
        }

        return new self(
            facebook: $data['facebook'] ?? null,
            instagram: $data['instagram'] ?? null,
            twitter: $data['twitter'] ?? null,
            linkedin: $data['linkedin'] ?? null,
        );
    }

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
