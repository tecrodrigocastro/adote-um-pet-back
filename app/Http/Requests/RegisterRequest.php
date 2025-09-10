<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}
