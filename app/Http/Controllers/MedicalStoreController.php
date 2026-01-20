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

    // public function sssstore(MedicalStoreRequest $request)
    // {
    //     dd($request->all());
    //     $data = $request->only([
    //         'Name','Slug','LicenseNumber','GSTIN','PAN','IsActive','IsFeatured',
    //         'OpenTime','CloseTime','RadiusKm','DeliveryFee','MinOrder',
    //         'Latitude','Longitude','Priority'
    //     ]);

    //     $data['MedicalStoreId'] = (string) Str::uuid();
    //     $data['IsActive'] = $request->has('IsActive') ? (bool)$request->get('IsActive') : true;
    //     $data['IsFeatured'] = $request->has('IsFeatured') ? (bool)$request->get('IsFeatured') : false;
    //     $data['CreatedAt'] = now();

    //     // handle image
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $filename = Str::slug($data['Name'] ?? 'store') . '-' . time() . '.' . $file->getClientOriginalExtension();
    //         $path = $file->storeAs('medicalstores', $filename, 'public');
    //         $data['ImageUrl'] = $path;
    //     }

    //     $store = MedicalStore::create($data);

    //     return redirect()->route('admin.medicalstores.index')->with('success', 'Medical store created.');
    // }

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
    public function allMedicalstores(Request $request)
    {
        $query = MedicalStore::whereHas('user', function ($q) {
            $q->where('Role', 2);
        })->with('user');

        // search by name, license, gstin, pan
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'ilike', "%{$search}%")
                    ->orWhere('LicenseNumber', 'ilike', "%{$search}%")
                    ->orWhere('GSTIN', 'ilike', "%{$search}%")
                    ->orWhere('PAN', 'ilike', "%{$search}%");
            });
        }

        /* Online / Offline filter (from select box) */
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus === 'true');
        }

        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5,10,25,50]) ? $perPage : 10;

        $users = $query->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            return view(
                'admin.business.medicalstore.searchedMedicalstore',
                compact('users')
            );
        }

        return view('admin.business.medicalstore.index', compact('users'));
    }


    /**
     * Store a new Medicalstore
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|exists:Users,Email',
            'LicenseNumber' => 'nullable|string|max:50',
            'GSTIN' => 'nullable|string|max:15',
            'PAN' => 'nullable|string|max:10',
            'OpenTime' => 'required|date_format:H:i',
            'CloseTime' => 'required|date_format:H:i',
            'RadiusKm' => 'nullable|numeric',
            'DeliveryFee' => 'required|numeric',
            'MinOrder' => 'required|numeric',
            'Address' => 'required|string',
            'Latitude' => 'nullable|numeric',
            'Longitude' => 'nullable|numeric',
        ]);

        // Get the UserId from the email
        $user = User::where('Email', $validated['email'])->first();
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User with this email does not exist'])->withInput();
        }

        // Check if the user already has a MedicalStore
        if (MedicalStore::where('UserId', $user->UserId)->exists()) {
            return redirect()->back()->withErrors([
                'user_id' => 'This Email already has a registered MedicalStore.'
            ])->withInput();
        }

        // Determine the next priority
        $lastPriority = MedicalStore::max('Priority') ?? 0; // get max priority in table, 0 if none

        // Create the MedicalStore
        $medicalStore = MedicalStore::create([
            // 'MedicalStoreId' => (string) Str::uuid(),
            'UserId' => $user->UserId, // from Users table
            'Name' => $validated['name'],
            // 'Slug' => Str::slug($validated['name']),
            'LicenseNumber' => $validated['LicenseNumber'],
            'GSTIN' => $validated['GSTIN'],
            'PAN' => $validated['PAN'],
            'IsActive' => $request->has('IsActive') ? true : false,
            'OpenTime' => $validated['OpenTime'],
            'CloseTime' => $validated['CloseTime'],
            'RadiusKm' => $validated['RadiusKm'],
            'DeliveryFee' => $validated['DeliveryFee'],
            'MinOrder' => $validated['MinOrder'],
            'Priority' => $lastPriority + 1, // set last priority +1
            'Address' => $validated['Address'],
            'Latitude' => $validated['Latitude'],
            'Longitude' => $validated['Longitude'],
            'CreatedAt' => now(),
        ]);

        return redirect()->route('medicalStores.index')->with('success', 'Medicalstore added successfully!');
    }
}
