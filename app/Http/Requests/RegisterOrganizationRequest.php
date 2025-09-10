<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}
