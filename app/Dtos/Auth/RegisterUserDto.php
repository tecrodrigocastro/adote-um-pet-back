<?php

namespace App\Dtos\Auth;

final readonly class RegisterUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $phone,
        public ?string $photoUrl,
        public AddressDto $address,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            phone: $data['phone'],
            photoUrl: $data['photo_url'] ?? null,
            address: AddressDto::fromArray($data['address']),
        );
    }
}
