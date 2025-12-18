<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalStoreRequest extends FormRequest
{
    public function authorize()
    {
        // adjust authorization as needed (e.g., admin only)
        return $this->user() != null;
    }

    public function rules()
    {
        $id = $this->route('id') ?? $this->route('medicalstore') ?? null;

        return [
            'Name' => 'required|string|max:191',
            'Slug' => 'nullable|string|max:191',
            'LicenseNumber' => 'nullable|string|max:100',
            'GSTIN' => 'nullable|string|max:50',
            'PAN' => 'nullable|string|max:50',
            'IsActive' => 'sometimes|boolean',
            'IsFeatured' => 'sometimes|boolean',
            'OpenTime' => 'nullable|date_format:H:i',
            'CloseTime' => 'nullable|date_format:H:i',
            'RadiusKm' => 'nullable|numeric|min:0',
            'DeliveryFee' => 'nullable|numeric|min:0',
            'MinOrder' => 'nullable|numeric|min:0',
            'Latitude' => 'nullable|numeric',
            'Longitude' => 'nullable|numeric',
            'Priority' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ];
    }
}
    