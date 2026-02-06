<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalStoreRequest;
use App\Models\MedicalStore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MedicalStoreController extends Controller
{


    /***************************** 
        ALL MEDICICALSTORES
    ***************************/
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

    /***************************** 
    ********* SHOW ***********
    ***************************/
    public function show($id)
    {
        $store = MedicalStore::findOrFail($id);
        return view('admin.users.medical_stores.show', compact('store'));
    }


    /***************************** 
    ********* UPDATE ***********
    ***************************/
    public function update(Request $request, $id)
    {
        // Find medical store
        $store = MedicalStore::findOrFail($id);

        // Find related user
        $user = User::where('UserId', $store->UserId)->firstOrFail();

        // Validate input
        $request->validate([
            'storeName'     => 'required|string|max:255',
            'adminName'     => 'required|string|max:255',

            // Ignore current user for unique validation
            'adminEmail'    => 'required|email|unique:Users,Email,' . $user->UserId . ',UserId',
            'adminPhone'    => 'required|unique:Users,Phone,' . $user->UserId . ',UserId',

            'storeAddress'  => 'required|string|max:255',

            // Ignore current store for unique validation
            'licenseNumber' => 'required|unique:MedicalStores,LicenseNumber,' . $store->MedicalStoreId . ',MedicalStoreId',
            'gstin'         => 'required|unique:MedicalStores,GSTIN,' . $store->MedicalStoreId . ',MedicalStoreId',
            'pan'           => 'required|unique:MedicalStores,PAN,' . $store->MedicalStoreId . ',MedicalStoreId',

            'openTime'      => 'required',
            'closeTime'     => 'required',

            'deliveryFee'   => 'nullable|numeric',
            'minOrder'      => 'nullable|numeric',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'IsActive'      => 'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update User (Admin)
        |--------------------------------------------------------------------------
        */
        $user->update([
            'Name'  => $request->adminName,
            'Email' => $request->adminEmail,
            'Phone' => $request->adminPhone,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Medical Store
        |--------------------------------------------------------------------------
        */
        $store->update([
            'Name'          => $request->storeName,
            'Slug'          => Str::slug($request->storeName),
            'LicenseNumber' => $request->licenseNumber,
            'GSTIN'         => $request->gstin,
            'PAN'           => $request->pan,
            'Address'       => $request->storeAddress,
            'OpenTime'      => $request->openTime,
            'CloseTime'     => $request->closeTime,
            'DeliveryFee'   => $request->deliveryFee,
            'MinOrder'      => $request->minOrder,
            'Latitude'      => $request->latitude,
            'Longitude'     => $request->longitude,
            'IsActive'      => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('admin.medicalstores.list')->with('success', 'Medical store updated successfully.');
    }


    /***************************** 
    ********* DESTROY ***********
    ***************************/
    public function destroy($id)
    {
        $store = MedicalStore::findOrFail($id);
        if ($store->ImageUrl && Storage::disk('public')->exists($store->ImageUrl)) {
            Storage::disk('public')->delete($store->ImageUrl);
        }
        $store->delete();
        return redirect()->route('admin.medicalstores.list')->with('success', 'Medical store deleted.');
    }


    /********* TOGGLE ACTIVE ***********
    *************************************/
    public function toggleActive($id)
    {
        $store = MedicalStore::findOrFail($id);
        $store->IsActive = !$store->IsActive;
        $store->save();
        return response()->json(['success' => true, 'IsActive' => $store->IsActive]);
    }



    // /***************************** 
    // ********* STORE ***********
    // ***************************/
    // public function store(Request $request)
    // {
    //     //Token check
    //     $token = session('jwt_token');

    //     if (!$token) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please login again.'
    //         ], 401);
    //     }

    //     //Validation
    //     $validated = $request->validate([
    //         'storeName'       => 'required|string|max:100|unique:MedicalStores,Name',
    //         'storeAddress'    => 'required|string|max:200',
    //         'licenseNumber'   => 'required|string|max:100|unique:MedicalStores,LicenseNumber',
    //         'gstin'           => 'required|string|max:50|unique:MedicalStores,GSTIN',
    //         'pan'             => 'required|string|max:50|unique:MedicalStores,PAN',

    //         'adminName'       => 'required|string|max:100',
    //         'adminEmail'      => 'required|email|unique:Users,Email',
    //         'adminPassword'   => 'required|string|min:8|max:50',
    //         'adminPhone'      => 'required|string|min:9|max:15',

    //         'openTime'        => 'required|date_format:H:i',
    //         'closeTime'       => 'required|date_format:H:i',
    //         'deliveryFee'     => 'required|numeric|min:0',
    //         'minOrder'        => 'required|numeric|min:0',

    //         'latitude'        => 'required|numeric',
    //         'longitude'       => 'required|numeric',
    //     ], 

    //     [
    //         // Custom messages
    //         'storeName.required'     => 'Store name is required.',
    //         'storeName.unique'       => 'This store name is already taken.',
    //         'storeName.max'          => 'Store name must not exceed 100 characters.',

    //         'storeAddress.required'  => 'Store address is required.',
    //         'storeAddress.max'       => 'Store address must not exceed 200 characters.',

    //         'licenseNumber.required' => 'License number is required.',
    //         'licenseNumber.unique'   => 'This license number is already registered.',
    //         'licenseNumber.max'      => 'License number must not exceed 100 characters.',

    //         'gstin.required'         => 'GSTIN is required.',
    //         'gstin.unique'           => 'This GSTIN is already registered.',
    //         'gstin.max'              => 'GSTIN must not exceed 50 characters.',

    //         'pan.required'           => 'PAN is required.',
    //         'pan.unique'             => 'This PAN is already registered.',
    //         'pan.max'                => 'PAN must not exceed 50 characters.',

    //         'adminName.required'     => 'Admin name is required.',
    //         'adminName.max'          => 'Admin name must not exceed 100 characters.',

    //         'adminEmail.required'    => 'Admin email is required.',
    //         'adminEmail.email'       => 'Admin email must be a valid email address.',
    //         'adminEmail.unique'      => 'This email is already registered.',

    //         'adminPassword.required' => 'Password is required.',
    //         'adminPassword.min'      => 'Password must be at least 8 characters.',
    //         'adminPassword.max'      => 'Password must not exceed 50 characters.',

    //         'adminPhone.required'    => 'Admin phone number is required.',
    //         'adminPhone.min'         => 'Phone number must be at least 9 digits.',
    //         'adminPhone.max'         => 'Phone number must not exceed 15 digits.',

    //         'openTime.required'      => 'Opening time is required.',
    //         'openTime.date_format'   => 'Opening time must be in H:i format (e.g., 09:00).',

    //         'closeTime.required'     => 'Closing time is required.',
    //         'closeTime.date_format'  => 'Closing time must be in H:i format (e.g., 21:00).',

    //         'deliveryFee.required'   => 'Delivery fee is required.',
    //         'deliveryFee.numeric'    => 'Delivery fee must be a number.',
    //         'deliveryFee.min'        => 'Delivery fee must be at least 0.',

    //         'minOrder.required'      => 'Minimum order amount is required.',
    //         'minOrder.numeric'       => 'Minimum order must be a number.',
    //         'minOrder.min'           => 'Minimum order must be at least 0.',

    //         'latitude.required'      => 'Latitude is required.',
    //         'latitude.numeric'       => 'Latitude must be a number.',

    //         'longitude.required'     => 'Longitude is required.',
    //         'longitude.numeric'      => 'Longitude must be a number.',
    //     ]);

    //     //API payload
    //     $payload = [
    //         'storeName'       => $validated['storeName'],
    //         'adminName'       => $validated['adminName'],
    //         'adminEmail'      => $validated['adminEmail'],
    //         'adminPassword'   => $validated['adminPassword'],
    //         'adminPhone'      => $validated['adminPhone'],

    //         'storeAddress'    => $validated['storeAddress'],
    //         'licenseNumber'   => $validated['licenseNumber'],
    //         'gstin'           => $validated['gstin'],
    //         'pan'             => $validated['pan'],

    //         'openTime'        => $validated['openTime'],
    //         'closeTime'       => $validated['closeTime'],
    //         'deliveryFee'     => $validated['deliveryFee'],
    //         'minOrder'        => $validated['minOrder'],

    //         'location' => [
    //             'latitude'  => $validated['latitude'],
    //             'longitude' => $validated['longitude'],
    //         ]
    //     ];

    //     // API call
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //         'Accept' => 'application/json',
            
    //     ])->post(
    //         'https://pcsdecom.azurewebsites.net/api/admin/register/medical-store',
    //         $payload
    //     );

    //     // API error handling
    //     // if ($response->failed()) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => $response->json('message') ?? 'API error'
    //     //     ], $response->status());
    //     // }

    //     if ($response->failed()) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => $response->status(),
    //             'body' => $response->body(),
    //             'json' => $response->json()
    //         ], $response->status());
    //     }

    //     // Success
    //     return response()->json([
    //         'success' => true,
    //         // 'message' => 'Medical store registered successfully'
    //     ]);
    // }

    public function store(Request $request)
{
    // Token check
    $token = session('jwt_token');
    if (!$token) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please login again.'
        ], 401);
    }

    // -------------------------
    // 1️⃣ Local validation
    // -------------------------
    try {
        $validated = $request->validate([
            'storeName'     => 'required|string|max:100|unique:MedicalStores,Name',
            'storeAddress'  => 'required|string|max:200',
            'licenseNumber' => 'required|string|max:100|unique:MedicalStores,LicenseNumber',
            'gstin'         => 'required|string|max:50|unique:MedicalStores,GSTIN',
            'pan'           => 'required|string|max:50|unique:MedicalStores,PAN',

            'adminName'     => 'required|string|max:100',
            'adminEmail'    => 'required|email|unique:Users,Email',
            'adminPassword' => 'required|string|min:8|max:50',
            'adminPhone'    => 'required|string|min:9|max:15',

            'openTime'      => 'required|date_format:H:i',
            'closeTime'     => 'required|date_format:H:i',
            'deliveryFee'   => 'required|numeric|min:0',
            'minOrder'      => 'required|numeric|min:0',

            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Return JSON for local validation errors
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    }

    // -------------------------
    // 2️⃣ API payload
    // -------------------------
    $payload = [
        'storeName'     => $validated['storeName'],
        'adminName'     => $validated['adminName'],
        'adminEmail'    => $validated['adminEmail'],
        'adminPassword' => $validated['adminPassword'],
        'adminPhone'    => $validated['adminPhone'],
        'storeAddress'  => $validated['storeAddress'],
        'licenseNumber' => $validated['licenseNumber'],
        'gstin'         => $validated['gstin'],
        'pan'           => $validated['pan'],
        'openTime'      => $validated['openTime'],
        'closeTime'     => $validated['closeTime'],
        'deliveryFee'   => $validated['deliveryFee'],
        'minOrder'      => $validated['minOrder'],
        'location'      => [
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]
    ];

    // -------------------------
    // 3️⃣ API request
    // -------------------------
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ])->post('https://pcsdecom.azurewebsites.net/api/admin/register/medical-store', $payload);

        if ($response->failed()) {
            // Handle API validation errors
            $apiJson = $response->json();

            return response()->json([
                'success' => false,
                'message' => $apiJson['message'] ?? 'Registration failed. OTP not sent.',
                'errors'  => $apiJson['errors'] ?? null,
                'status'  => $response->status(),
            ], $response->status());
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'API request failed: ' . $e->getMessage()
        ], 500);
    }

    // -------------------------
    // 4️⃣ Success
    // -------------------------
    return response()->json([
        'success' => true,
        'message' => 'Medical store registered successfully'
    ]);
}


    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', [
            'email' => $request->email,
            'code'  => $request->otp,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'OTP invalid or expired'
            ], $response->status() ?: 422);
        }

        $data = $response->json();

        return response()->json([
            'success' => true,
            'message' => $data['message'] ?? 'Medicalstore created successfully.',
            'redirect' => route('admin.medicalstores.list'),
        ]);
    }



    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/resend-verification', [
            'email' => $request->email,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to resend OTP'
            ], $response->status() ?: 500);
        }

        return response()->json([
            'success' => true,
            'message' => $response->json('message') ?? 'OTP sent successfully'
        ]);
    }
}
