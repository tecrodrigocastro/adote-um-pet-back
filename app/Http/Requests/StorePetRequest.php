<?php

namespace App\Http\Requests;

use App\Enums\PetGender;
use App\Enums\PetSize;
use App\Enums\PetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(PetType::values())],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'gender' => ['required', Rule::in(PetGender::values())],
            'size' => ['required', Rule::in(PetSize::values())],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'breed' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'photos' => ['required', 'array', 'min:1', 'max:5'],
            'photos.*' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120', // 5MB
                'dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000',
            ],
        ];
    }
}
