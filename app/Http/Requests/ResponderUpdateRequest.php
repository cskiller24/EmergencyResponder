<?php

namespace App\Http\Requests;

use App\Enums\ResponderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ResponderUpdateRequest extends FormRequest
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
            'emergency_type_id' => ['required', 'exists:emergency_types,id'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'status' => ['required', new Enum(ResponderStatusEnum::class)],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],

            'links' => ['required', 'array'],
            'links.*.link' => ['required', 'url'],

            'contacts' => ['required', 'array'],
            'contacts.*.type' => ['required'],
            'contacts.*.detail' => ['required'],

            'city' => ['required', 'max:255'],
            'region' => ['required', 'max:255'],
            'country' => ['required', 'max:255'],
            'zip' => ['required', 'max:255'],
            'line' => ['required', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'links.*.link' => [
                'required' => 'One of the links is not filled',
                'url' => 'One of the links is not a valid url',
            ],
            'contacts.*.type.required' => 'One of contact type is required',
            'contacts.*.detail.required' => 'One of contact detail is required',
            'emergency_type_id.required' => 'The emergency Type field is required',
            'emergency_type_id.exists' => 'The emergency type does not exists',
        ];
    }
}
