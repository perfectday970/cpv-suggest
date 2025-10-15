<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CpvSuggestRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'language' => ['nullable', 'in:de,en,fr,it,es'],
            'top_k' => ['nullable', 'integer', 'min:1', 'max:25'],
            'specificity' => ['nullable', 'integer', 'in:1,2,3'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'url.url' => 'The URL must be a valid web address.',
            'url.max' => 'The URL may not be longer than 2048 characters.',
            'description.max' => 'The description may not be longer than 5000 characters.',
            'language.in' => 'The language must be one of: de, en, fr, it, es.',
            'top_k.integer' => 'The top_k value must be an integer.',
            'top_k.min' => 'The top_k value must be at least 1.',
            'top_k.max' => 'The top_k value may not be greater than 25.',
            'specificity.integer' => 'The specificity value must be an integer.',
            'specificity.in' => 'The specificity value must be 1 (specific), 2 (medium), or 3 (general).',
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Ensure at least one of url or description is provided
        if (empty($this->url) && empty($this->description)) {
            throw new \Illuminate\Validation\ValidationException(
                validator: validator([], []),
                response: response()->json([
                    'message' => 'Either url or description must be provided.',
                    'errors' => [
                        'url' => ['Either url or description must be provided.'],
                        'description' => ['Either url or description must be provided.'],
                    ],
                ], 422)
            );
        }
    }
}
