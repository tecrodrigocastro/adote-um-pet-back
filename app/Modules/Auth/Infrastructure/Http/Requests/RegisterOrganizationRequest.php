<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Http\Requests;

use App\Modules\Auth\Application\DTOs\RegisterOrganizationDto;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterOrganizationRequest',
    required: ['email', 'password', 'organization_name', 'cnpj', 'responsible_name', 'phone', 'mission_statement', 'address'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'org@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
        new OA\Property(property: 'organization_name', type: 'string', example: 'Abrigo Patinhas Felizes'),
        new OA\Property(property: 'cnpj', type: 'string', example: '12345678000199'),
        new OA\Property(property: 'responsible_name', type: 'string', example: 'Maria Souza'),
        new OA\Property(property: 'phone', type: 'string', example: '1234567890'),
        new OA\Property(property: 'mission_statement', type: 'string', example: 'Resgatar e encontrar lares para animais abandonados.'),
        new OA\Property(property: 'website', type: 'string', example: 'https://patinhasfelizes.org', nullable: true),
        new OA\Property(
            property: 'social_media',
            type: 'object',
            nullable: true,
            properties: [
                new OA\Property(property: 'facebook', type: 'string', example: 'https://facebook.com/patinhasfelizes', nullable: true),
                new OA\Property(property: 'instagram', type: 'string', example: 'https://instagram.com/patinhasfelizes', nullable: true),
                new OA\Property(property: 'twitter', type: 'string', example: 'https://twitter.com/patinhasfelizes', nullable: true),
                new OA\Property(property: 'linkedin', type: 'string', example: 'https://linkedin.com/company/patinhasfelizes', nullable: true),
            ],
        ),
        new OA\Property(
            property: 'address',
            required: ['street', 'neighborhood', 'number_house', 'zip_code', 'city', 'state'],
            properties: [
                new OA\Property(property: 'street', type: 'string', example: 'Rua das Flores'),
                new OA\Property(property: 'neighborhood', type: 'string', example: 'Centro'),
                new OA\Property(property: 'number_house', type: 'integer', example: 123),
                new OA\Property(property: 'complement', type: 'string', example: 'Sala 2', nullable: true),
                new OA\Property(property: 'zip_code', type: 'string', example: '12345-678'),
                new OA\Property(property: 'city', type: 'string', example: 'São Paulo'),
                new OA\Property(property: 'state', type: 'string', example: 'SP'),
            ],
            type: 'object',
        ),
    ],
)]
class RegisterOrganizationRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'organization_name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:14|unique:users',
            'responsible_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'mission_statement' => 'required|string|max:1000',
            'website' => 'nullable|url',
            'social_media' => 'nullable|array',
            'social_media.facebook' => 'nullable|url',
            'social_media.instagram' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
            'social_media.linkedin' => 'nullable|url',

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
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'O email informado já está em uso',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres',
            'organization_name.required' => 'O nome da organização é obrigatório',
            'cnpj.required' => 'CNPJ é obrigatório para organizações',
            'cnpj.size' => 'CNPJ deve ter 14 dígitos',
            'cnpj.unique' => 'CNPJ já cadastrado',
            'responsible_name.required' => 'Nome do responsável é obrigatório',
            'phone.required' => 'O campo telefone é obrigatório',
            'mission_statement.required' => 'A declaração de missão é obrigatória',
            'mission_statement.max' => 'A declaração de missão não pode ter mais de 1000 caracteres',
            'website.url' => 'O website deve ser uma URL válida',
            'social_media.facebook.url' => 'O Facebook deve ser uma URL válida',
            'social_media.instagram.url' => 'O Instagram deve ser uma URL válida',
            'social_media.twitter.url' => 'O Twitter deve ser uma URL válida',
            'social_media.linkedin.url' => 'O LinkedIn deve ser uma URL válida',

            'address.required' => 'As informações de endereço são obrigatórias',
            'address.street.required' => 'O campo rua é obrigatório',
            'address.neighborhood.required' => 'O campo bairro é obrigatório',
            'address.number_house.required' => 'O campo número é obrigatório',
            'address.zip_code.required' => 'O campo CEP é obrigatório',
            'address.city.required' => 'O campo cidade é obrigatório',
            'address.state.required' => 'O campo estado é obrigatório',
            'address.state.max' => 'O estado deve ter no máximo 2 caracteres',
        ];
    }

    public function toDto(): RegisterOrganizationDto
    {
        return RegisterOrganizationDto::from($this->validated());
    }
}
