<?php

namespace App\Dtos\Auth;

final readonly class AddressDto
{
    public function __construct(
        public string $street,
        public string $neighborhood,
        public int $numberHouse,
        public ?string $complement,
        public string $zipCode,
        public string $city,
        public string $state,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            street: $data['street'],
            neighborhood: $data['neighborhood'],
            numberHouse: (int) $data['number_house'],
            complement: $data['complement'] ?? null,
            zipCode: $data['zip_code'],
            city: $data['city'],
            state: $data['state'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'number_house' => $this->numberHouse,
            'complement' => $this->complement,
            'zip_code' => $this->zipCode,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
