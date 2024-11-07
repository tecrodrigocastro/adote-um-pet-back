<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
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
            'name' => ['string', 'max:255'],
            'type' => ['string', 'max:255'],
            'user_id' => ['integer', 'exists:users,id'],
            'gender' => ['string', 'max:255'],
            'size' => ['string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'breed' => ['string', 'max:255'],
            'color' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'description' => ['string'],
            'photos' => ['array'],

        ];
    }
}
