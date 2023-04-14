<?php

namespace App\Http\Requests;

use App\Enums\SubmissionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class SubmissionUpdateRequest extends FormRequest
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
            'submitter_notify' => ['required', 'boolean'],
            'emergency_type_id' => ['required', 'exists:emergency_types,id'],
            'status' => ['required', new Enum(SubmissionStatusEnum::class)],
            'name' => ['required', 'max:255'],
            'description' => ['required'],

            'links' => ['required', 'array'],
            'links.*.link' => ['required', 'url'],

            'contacts' => ['required', 'array'],
            'contacts.*.type' => ['required'],
            'contacts.*.detail' => ['required'],

            'longitude' => ['required', 'max:255', 'numeric'],
            'latitude' => ['required', 'max:255', 'numeric'],
            'city' => ['required'],
            'region' => ['required'],
            'country' => ['required'],
            'zip' => ['required'],
        ];
    }

    public function getValidatorInstance(): Validator
    {
        if ($this->has('submitter_notify') && $this->get('submitter_notify') === 'on') {
            $this->merge([
                'submitter_notify' => true,
            ]);
        }

        if (! $this->has('notify')) {
            $this->merge([
                'submitter_notify' => false,
            ]);
        }

        return parent::getValidatorInstance();
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
