<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineCategoryRequest extends FormRequest
{
    public function authorize()
    {
        // Adjust authorization as required (e.g. admin-only)
        return auth()->check();
    }

    public function rules()
    {
        $id = $this->route('medicine_category') ?? $this->route('id'); // supports route model or id

        return [
            'Name' => ['required', 'string', 'max:191'],
            'Description' => ['nullable', 'string'],
            'IsActive' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation()
    {
        // Normalize IsActive checkbox to boolean
        if ($this->has('IsActive')) {
            $this->merge(['IsActive' => (bool) $this->input('IsActive')]);
        } else {
            $this->merge(['IsActive' => false]);
        }
    }
}
