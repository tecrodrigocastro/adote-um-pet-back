<?php

namespace App\Dtos\Auth;

final readonly class RegisterOrganizationDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $organizationName,
        public string $cnpj,
        public string $responsibleName,
        public string $phone,
        public string $missionStatement,
        public ?string $website,
        public ?SocialMediaDto $socialMedia,
        public AddressDto $address,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            organizationName: $data['organization_name'],
            cnpj: $data['cnpj'],
            responsibleName: $data['responsible_name'],
            phone: $data['phone'],
            missionStatement: $data['mission_statement'],
            website: $data['website'] ?? null,
            socialMedia: SocialMediaDto::fromArray($data['social_media'] ?? null),
            address: AddressDto::fromArray($data['address']),
        );
    }
}
