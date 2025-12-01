<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'note' => [
                'required',
                'string',
                'min:10',
                'max:10000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'note.required' => 'Please enter a note.',
            'note.min' => 'Note must be at least 10 characters.',
            'note.max' => 'Note cannot exceed 10,000 characters.',
        ];
    }
}
