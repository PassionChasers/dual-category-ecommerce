<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalStoreRequest;
use App\Models\MedicalStore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MedicalStoreController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalStore::query();

        // search by name, license, gstin, pan
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'ilike', "%{$search}%")
                    ->orWhere('LicenseNumber', 'ilike', "%{$search}%")
                    ->orWhere('GSTIN', 'ilike', "%{$search}%")
                    ->orWhere('PAN', 'ilike', "%{$search}%");
            });
        }

        // status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') $query->where('IsActive', true);
            if ($request->status === 'inactive') $query->where('IsActive', false);
        }

        // sort & pagination
        $allowedSorts = ['Name', 'CreatedAt', 'Priority'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5,10,25,50]) ? $perPage : 10;

        $medicalstores = $query->paginate($perPage)->appends($request->except('page'));

        return view('admin.users.medical_stores.searchedMedicalstore', [
            'medicalstores' => $medicalstores,
        ]);
    }

    public function store(MedicalStoreRequest $request)
    {
        dd($request->all());
        $data = $request->only([
            'Name','Slug','LicenseNumber','GSTIN','PAN','IsActive','IsFeatured',
            'OpenTime','CloseTime','RadiusKm','DeliveryFee','MinOrder',
            'Latitude','Longitude','Priority'
        ]);

        $data['MedicalStoreId'] = (string) Str::uuid();
        $data['IsActive'] = $request->has('IsActive') ? (bool)$request->get('IsActive') : true;
        $data['IsFeatured'] = $request->has('IsFeatured') ? (bool)$request->get('IsFeatured') : false;
        $data['CreatedAt'] = now();

        // handle image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? 'store') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('medicalstores', $filename, 'public');
            $data['ImageUrl'] = $path;
        }

        $store = MedicalStore::create($data);

        return redirect()->route('admin.medicalstores.index')->with('success', 'Medical store created.');
    }

    public function show($id)
    {
        $store = MedicalStore::findOrFail($id);
        return view('admin.users.medical_stores.show', compact('store'));
    }

    public function update(Request $request, string $id)
    {
        $store = MedicalStore::findOrFail($id);

        $data = $request->only([
            'Name','Slug','LicenseNumber','GSTIN','PAN','IsActive','IsFeatured',
            'OpenTime','CloseTime','RadiusKm','DeliveryFee','MinOrder',
            'Latitude','Longitude','Priority'
        ]);

        $data['IsActive'] = $request->has('IsActive') ? (bool)$request->get('IsActive') : $store->IsActive;
        $data['IsFeatured'] = $request->has('IsFeatured') ? (bool)$request->get('IsFeatured') : $store->IsFeatured;

        if ($request->hasFile('image')) {
            // delete old
            if ($store->ImageUrl && Storage::disk('public')->exists($store->ImageUrl)) {
                Storage::disk('public')->delete($store->ImageUrl);
            }
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? $store->Name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('medicalstores', $filename, 'public');
            $data['ImageUrl'] = $path;
        }

        $store->update($data);

        return redirect()->route('admin.medicalstores.list')->with('success','Medical store updated.');
    }

    public function destroy($id)
    {
       
        

        $store = MedicalStore::findOrFail($id);
        if ($store->ImageUrl && Storage::disk('public')->exists($store->ImageUrl)) {
            Storage::disk('public')->delete($store->ImageUrl);
        }
        $store->delete();

        return redirect()->route('admin.medicalstores.list')->with('success', 'Medical store deleted.');
       
    }

    public function toggleActive($id)
    {
        $store = MedicalStore::findOrFail($id);
        $store->IsActive = !$store->IsActive;
        $store->save();
        return response()->json(['success' => true, 'IsActive' => $store->IsActive]);
    }

     /**
     * all medicine stores 
     */
    public function allMedicalstores()
    {
        $medicalstores = MedicalStore::with('user')->paginate(4);

        return view('admin.users.medical_stores.index', compact('medicalstores'));
    }
}
