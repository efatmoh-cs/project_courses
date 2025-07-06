<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrades extends FormRequest
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
            'name.en' => 'required',
             'name.ar' => 'required|string|max:255|unique:grades,name->ar',
            'notes.en' => 'nullable|string',
            'notes.ar' => 'nullable|string',
        ];
    }
     public function messages()
    {
        return [
            'name.required' => trans('validation.required'),

        ];
    }
}
