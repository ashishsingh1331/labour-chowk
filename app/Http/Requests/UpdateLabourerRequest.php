<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLabourerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:2', 'max:80'],
            'phone_e164' => ['required', 'string', 'max:20', 'regex:/^\\+[1-9]\\d{7,19}$/'],
            'area_id' => ['required', 'integer', 'exists:areas,id'],
            'skill_ids' => ['sometimes', 'array'],
            'skill_ids.*' => ['integer', 'exists:skills,id'],
            'is_active' => ['sometimes', 'boolean'],
            'photo' => ['sometimes', 'file', 'image', 'max:4096'],
        ];
    }
}
