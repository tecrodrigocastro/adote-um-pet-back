<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pet = $this->route('pet');

        // Não pode adotar próprio pet
        if ($pet && $pet->user_id === auth()->id()) {
            return false;
        }

        // Pet deve estar disponível para adoção
        if ($pet && ! in_array($pet->status, ['unadopted', 'pending'])) {
            return false;
        }

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
            'message' => 'required|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Uma mensagem é obrigatória para solicitar a adoção.',
            'message.max' => 'A mensagem não pode ter mais de 500 caracteres.',
        ];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        $pet = $this->route('pet');

        if ($pet && $pet->user_id === auth()->id()) {
            abort(403, 'Você não pode adotar seu próprio pet.');
        }

        if ($pet && $pet->status === 'adopted') {
            abort(403, 'Este pet já foi adotado.');
        }

        abort(403, 'Você não tem permissão para fazer esta solicitação.');
    }
}
