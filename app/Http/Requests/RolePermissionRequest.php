<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
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
            'permissions' => ['sometimes', 'required', 'array'],
            'permissions.*' => ['exists:'.config('permission.table_names.permissions').',name'],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.*.exists' => 'Permission does not exists',
        ];
    }
}
