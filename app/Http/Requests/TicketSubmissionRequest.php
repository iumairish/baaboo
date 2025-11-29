<?php

namespace App\Http\Requests;

use App\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public form, no authentication required
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],
            'subject' => [
                'required',
                'string',
                'max:500',
                'min:5',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
            'type' => [
                'required',
                'string',
                Rule::enum(TicketType::class),
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
            'name.required' => 'Please enter your name.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'subject.required' => 'Please enter a subject for your ticket.',
            'subject.min' => 'Subject must be at least 5 characters.',
            'description.required' => 'Please describe your issue.',
            'description.min' => 'Description must be at least 10 characters.',
            'type.required' => 'Please select a ticket type.',
            'type.enum' => 'Please select a valid ticket type.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email address',
            'subject' => 'subject',
            'description' => 'description',
            'type' => 'ticket type',
        ];
    }
}