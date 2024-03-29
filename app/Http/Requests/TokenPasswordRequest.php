<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenPasswordRequest extends FormRequest
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
            'token_reset_password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute' . __('validation.required'),
            'exists' => ':attribute' . __('validation.exists'),
        ];
    }

    public function attributes(): array
    {
        return [
            'token_reset_password' => __('validation.token_reset_password'),
        ];
    }
}
