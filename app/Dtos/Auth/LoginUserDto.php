<?php

namespace App\Dtos\Auth;

final readonly class LoginUserDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }

    /**
     * @return array{email: string, password: string}
     */
    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
