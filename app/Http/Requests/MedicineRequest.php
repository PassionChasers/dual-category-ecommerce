<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // change if you want policy checks
    }

    public function rules()
    {
        $rules = [
            'MedicalStoreId' => ['nullable', 'integer'],
            'MedicineCategoryId' => ['nullable'], // keep flexible (int or uuid) — validate in controller if strict
            'Name' => ['required', 'string', 'max:191'],
            'GenericName' => ['nullable', 'string', 'max:191'],
            'BrandName' => ['nullable', 'string', 'max:191'],
            'Description' => ['nullable', 'string'],
            'Price' => ['required', 'numeric', 'min:0'],
            'MRP' => ['nullable', 'numeric', 'min:0'],
            'PrescriptionRequired' => ['nullable', 'boolean'],
            'Manufacturer' => ['nullable', 'string', 'max:191'],
            'ExpiryDate' => ['nullable', 'date'],
            'DosageForm' => ['nullable', 'string', 'max:100'],
            'Strength' => ['nullable', 'string', 'max:100'],
            'Packaging' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'IsActive' => ['nullable', 'boolean'],
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'PrescriptionRequired' => $this->has('PrescriptionRequired') ? 1 : 0,
            'IsActive' => $this->has('IsActive') ? 1 : 0,
        ]);
    }
}
