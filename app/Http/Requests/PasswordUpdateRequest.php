<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'current_password.current_password' => 'The provided password does not match your current password.'
        ];
    }
}
