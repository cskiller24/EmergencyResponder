<?php

namespace App\Http\Requests;

use App\Enums\SubmissionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SubmissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'notify' => ['required', 'boolean'],
            'emegency_type_id' => ['required', 'exists:emergency_types,id'],
            'status' => ['required', new Enum(SubmissionStatusEnum::class)],
            'name' => ['required', 'max:255'],
            'description' => ['required'],

            'links' => ['required', 'array'],
            'links.*' => ['required', 'url'],

            'contacts' => ['required', 'array'],
            'contacts.*.type' => ['required'],
            'contacts.*.detail' => ['required'],

            'longitude' => ['required', 'max:255'],
            'latitude' => ['required', 'max:255'],
            'city' => ['required'],
            'region' => ['required'],
            'country' => ['required'],
            'zip' => ['required']
        ];;
    }
}
