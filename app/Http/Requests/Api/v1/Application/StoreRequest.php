<?php

namespace App\Http\Requests\Api\v1\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'guardian_first_name' => 'required|max:100',
            'guardian_last_name' => 'required|max:100',
            'guardian_email' => 'required|email|max:100|unique:users,email',
            'guardian_contact_number' => 'required|max:100',
            'student_first_name' => 'required|max:100',
            'student_last_name' => 'required|max:100',
            'student_birth_date' => 'required|date_format:Y-m-d',
            'student_middle_name' => 'nullable|max:100'
        ];
    }
}
