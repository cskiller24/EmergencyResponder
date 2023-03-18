<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
        // unique:permissions,name
        return [
            'name' => ['required', 'max:255', 'unique:'.config('permission.table_names.permissions').',name'],
        ];
    }

    public function getValidatorInstance(): Validator
    {
        $this->snakeName();

        return parent::getValidatorInstance();
    }

    protected function snakeName(): void
    {
        if ($this->request->has('name')) {
            $this->merge([
                'name' => str_snake($this->request->get('name')),
            ]);
        }
    }
}
