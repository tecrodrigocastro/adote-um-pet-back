<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
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
            'adopter_id' => 'required|exists:users,id',
            'owner_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
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
            'adopter_id.required' => 'O campo adopter_id é obrigatório',
            'adopter_id.exists' => 'O campo adopter_id deve ser um id de usuário válido',
            'owner_id.required' => 'O campo owner_id é obrigatório',
            'owner_id.exists' => 'O campo owner_id deve ser um id de usuário válido',
            'pet_id.required' => 'O campo pet_id é obrigatório',
            'pet_id.exists' => 'O campo pet_id deve ser um id de pet válido',
        ];
    }
}
