<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Search name, brand, generic, description
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                    ->orWhere('BrandName', 'like', "%{$search}%")
                    ->orWhere('GenericName', 'like', "%{$search}%")
                    ->orWhere('Description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('MedicineCategoryId', $category);
        }

        // Filter by prescription required
        if ($request->filled('prescription')) {
            if ($request->prescription === 'yes')
                $query->where('PrescriptionRequired', true);
            elseif ($request->prescription === 'no')
                $query->where('PrescriptionRequired', false);
        }

        // Filter by is active
        if ($request->filled('status')) {
            if ($request->status === 'active')
                $query->where('IsActive', true);
            elseif ($request->status === 'inactive')
                $query->where('IsActive', false);
        }

        // Sorting
        $allowedSorts = ['Name', 'Price', 'MRP', 'CreatedAt'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25, 50]) ? $perPage : 10;

        $medicines = $query->paginate($perPage)->appends($request->except('page'));

        // Lightweight list of categories for filters (if your categories live in MedicineCategory model)
        $categories = \App\Models\MedicineCategory::select('MedicineCategoryId', 'Name')->orderBy('Name')->get();

        return view('admin.products.medicine.index', compact('medicines', 'categories'));
    }

    public function store(MedicineRequest $request)
    {
        $data = $request->only([
            'MedicalStoreId',
            'MedicineCategoryId',
            'Name',
            'GenericName',
            'BrandName',
            'Description',
            'Price',
            'MRP',
            'PrescriptionRequired',
            'Manufacturer',
            'ExpiryDate',
            'DosageForm',
            'Strength',
            'Packaging',
            'IsActive'
        ]);

        // Generate UUID and set as primary key
        $data['MedicineId'] = (string) Str::uuid();

        // handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? 'medicine') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('medicines', $filename, 'public');
            $data['ImageUrl'] = $path; // store relative path like "medicines/xxx.jpg"
        }

        $medicine = Medicine::create($data);

        return redirect()->route('admin.medicines.index')->with('success', "Medicine '{$medicine->Name}' created.");
    }

    public function update(MedicineRequest $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $data = $request->only([
            'MedicalStoreId',
            'MedicineCategoryId',
            'Name',
            'GenericName',
            'BrandName',
            'Description',
            'Price',
            'MRP',
            'PrescriptionRequired',
            'Manufacturer',
            'ExpiryDate',
            'DosageForm',
            'Strength',
            'Packaging',
            'IsActive'
        ]);

        // image update: delete old if exists
        if ($request->hasFile('image')) {
            if ($medicine->ImageUrl && Storage::disk('public')->exists($medicine->ImageUrl)) {
                Storage::disk('public')->delete($medicine->ImageUrl);
            }
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? 'medicine') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('medicines', $filename, 'public');
            $data['ImageUrl'] = $path;
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')->with('success', "Medicine '{$medicine->Name}' updated.");
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        // delete image if present
        if ($medicine->ImageUrl && Storage::disk('public')->exists($medicine->ImageUrl)) {
            Storage::disk('public')->delete($medicine->ImageUrl);
        }
        $name = $medicine->Name;
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', "Medicine '{$name}' deleted.");
    }

    public function toggleActive($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->IsActive = !$medicine->IsActive;
        $medicine->save();

        return response()->json(['success' => true, 'IsActive' => $medicine->IsActive]);
    }
}
