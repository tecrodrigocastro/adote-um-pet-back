<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Http\Requests;

use App\Modules\Auth\Application\DTOs\RegisterUserDto;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterUserRequest',
    required: ['name', 'email', 'password', 'phone', 'address'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'client@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
        new OA\Property(property: 'phone', type: 'string', example: '1234567890'),
        new OA\Property(property: 'photo_url', type: 'string', example: 'https://example.com/photo.jpg', nullable: true),
        new OA\Property(
            property: 'address',
            required: ['street', 'neighborhood', 'number_house', 'zip_code', 'city', 'state'],
            properties: [
                new OA\Property(property: 'street', type: 'string', example: 'Rua das Flores'),
                new OA\Property(property: 'neighborhood', type: 'string', example: 'Centro'),
                new OA\Property(property: 'number_house', type: 'integer', example: 123),
                new OA\Property(property: 'complement', type: 'string', example: 'Apto 4B', nullable: true),
                new OA\Property(property: 'zip_code', type: 'string', example: '12345-678'),
                new OA\Property(property: 'city', type: 'string', example: 'São Paulo'),
                new OA\Property(property: 'state', type: 'string', example: 'SP'),
            ],
            type: 'object',
        ),
    ],
)]
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:255',
            'photo_url' => 'nullable|string|max:255',

            // Campos do endereço
            'address' => 'required|array',
            'address.street' => 'required|string|max:255',
            'address.neighborhood' => 'required|string|max:255',
            'address.number_house' => 'required|integer',
            'address.complement' => 'nullable|string|max:255',
            'address.zip_code' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'required|string|max:2',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'O email informado já está em uso',
            'password.required' => 'O campo senha é obrigatório',
            'phone.required' => 'O campo telefone é obrigatório',

            'address.required' => 'As informações de endereço são obrigatórias',
            'address.street.required' => 'O campo rua é obrigatório',
            'address.neighborhood.required' => 'O campo bairro é obrigatório',
            'address.number_house.required' => 'O campo número da casa é obrigatório',
            'address.zip_code.required' => 'O campo CEP é obrigatório',
            'address.city.required' => 'O campo cidade é obrigatório',
            'address.state.required' => 'O campo estado é obrigatório',
            'address.state.max' => 'O estado deve ter no máximo 2 caracteres',
        ];
    }

    public function toDto(): RegisterUserDto
    {
        return RegisterUserDto::from($this->validated());
    }
}
